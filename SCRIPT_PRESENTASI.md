# 📋 Script Presentasi — Web Sewa Perlengkapan Outdoor
**Bagian: Profil, Transaksi, Cart, Checkout, Transaction & TransactionItem**

---

## 🎬 PEMBUKA

> *"Baik, saya akan menjelaskan bagian yang saya kerjakan, yaitu alur transaksi — mulai dari keranjang belanja, proses checkout, hingga pembuatan data transaksi di database. Saya juga akan menghubungkannya ke 8 konsep yang sudah kita pelajari."*

---

## 1️⃣ RESPONSIVE DESIGN

**Yang dijelaskan:** Tampilan menyesuaikan ukuran layar di semua halaman.

> *"Web ini menggunakan Responsive Design. Kalau kita lihat di halaman Katalog, saya menggunakan CSS Grid dengan `auto-fill` dan `minmax` supaya card produknya otomatis menyesuaikan lebar layar."*

**Tunjukkan di kode — `katalog/index.blade.php`:**
```css
.kl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 22px;
}
```

> *"Di layar besar muncul 4-5 kolom, di HP jadi 2 kolom, bahkan 1 kolom. Kita juga pakai `@media query` untuk handle ini:"*

```css
@media (max-width: 640px) {
    .kl-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 380px) {
    .kl-grid { grid-template-columns: 1fr; }
}
```

> *"Di halaman Cart juga ada Sticky Bottom Bar yang posisinya `fixed`, dan saya sesuaikan lebarnya secara dinamis lewat JavaScript:"*

```javascript
function adjustStickyWidth() {
    const bottomBar = document.getElementById('sticky-bottom-bar');
    bottomBar.style.left = window.innerWidth <= 768 ? '0' : '260px';
}
window.addEventListener('resize', adjustStickyWidth);
```

> *"Jadi di mobile, bar-nya mulai dari kiri layar. Di desktop, dia menggeser 260px ke kanan karena ada sidebar."*

---

## 2️⃣ HTML, CSS, JAVASCRIPT, PHP, AJAX

**Yang dijelaskan:** Kelima teknologi dipakai sekaligus dalam satu alur transaksi.

> *"Kelima teknologi ini semuanya ada dalam alur transaksi kita. Saya jelaskan satu per satu."*

**HTML** — Struktur halaman cart (`cart/index.blade.php`):
> *"HTML kita pakai untuk struktur. Contohnya form checkout dengan hidden input untuk kirim item yang dipilih:"*
```html
<form action="{{ route('cart.checkout') }}" method="GET" id="checkout-form">
    <input type="checkbox" name="items[]" value="{{ $cart->id }}" class="item-checkbox">
</form>
```

**CSS** — Styling card transaksi (`dashboard.css`):
> *"CSS untuk tampilan. Misalnya badge status transaksi punya warna berbeda tergantung statusnya:"*
```css
.status-pending   { background-color: #fef08a; color: #854d0e; }  /* Kuning */
.status-completed { background-color: #bbf7d0; color: #166534; }  /* Hijau  */
.status-in-progress { background-color: #fecdd3; color: #9f1239; } /* Merah */
```

**JavaScript** — Kalkulasi harga real-time di checkout (`cart/checkout.blade.php`):
> *"JavaScript kita pakai untuk hitung total sewa secara langsung tanpa perlu kirim ke server dulu. Waktu user ganti tanggal, harga langsung berubah:"*
```javascript
function calculateRental() {
    const start = new Date(startDateInput.value);
    const end   = new Date(endDateInput.value);
    let durationDays = Math.ceil(Math.abs(end - start) / (1000 * 3600 * 24));

    let subtotal = 0;
    document.querySelectorAll('.item-qty-text').forEach(el => {
        const price = parseInt(el.getAttribute('data-price'));
        const qty   = parseInt(el.getAttribute('data-qty'));
        subtotal += price * qty * durationDays;
    });

    subtotalDisplay.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
}
```

**PHP** — Proses di server (`CartController.php`):
> *"PHP di sisi server memproses data yang dikirim. Misalnya waktu checkout, PHP hitung durasi sewa dan buat invoice:"*
```php
$durationDays  = $start->diffInDays($end);
$invoiceNumber = 'TRX-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
```

**AJAX** — Update keranjang tanpa reload (`cart/index.blade.php`):
> *"Dan AJAX kita pakai supaya update qty dan hapus item tidak perlu reload halaman. Pakai `fetch()` API:"*
```javascript
fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept':       'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({ action: 'increase' })
})
.then(res => res.json())
.then(data => {
    if (data.success) {
        document.getElementById('qty-' + cartId).textContent = data.new_qty + 'x';
        document.getElementById('subtotal-' + cartId).textContent = data.new_subtotal;
    }
});
```

> *"Server mengembalikan JSON, lalu JavaScript langsung update tampilan tanpa refresh."*

---

## 3️⃣ FORM

**Yang dijelaskan:** Ada banyak form dalam alur transaksi, masing-masing punya fungsi berbeda.

> *"Ada beberapa form dalam alur transaksi ini."*

**Form 1 — Pilih Item di Cart:**
> *"Form pertama di halaman Cart. User centang barang yang mau disewa, lalu klik checkout. Form ini pakai method GET karena hanya passing ID item:"*
```html
<form action="{{ route('cart.checkout') }}" method="GET" id="checkout-form">
    <input type="checkbox" name="items[]" value="{{ $cart->id }}">
    <button type="submit">Lanjut ke Rincian Sewa</button>
</form>
```

**Form 2 — Form Checkout:**
> *"Form kedua adalah form konfirmasi sewa. Ini pakai method POST karena ada data yang disimpan. Di sini user isi tanggal mulai, tanggal selesai, dan lokasi pengambilan:"*
```html
<form action="{{ route('cart.processCheckout') }}" method="POST">
    @csrf
    @foreach($selectedIds as $id)
        <input type="hidden" name="items[]" value="{{ $id }}">
    @endforeach
    <input type="date" name="start_date" required>
    <input type="date" name="end_date"   required>
    <textarea name="pickup_location" required></textarea>
    <button type="submit">Pilih Metode Pembayaran</button>
</form>
```

> *"Kita pakai `@csrf` — ini adalah token keamanan Laravel untuk mencegah serangan CSRF. Setiap form POST harus ada ini."*

**Form 3 — Form Pembayaran:**
> *"Form ketiga adalah pilih metode pembayaran. Pakai radio button, dan ada validasi `required` supaya user tidak bisa skip:"*
```html
<form action="{{ route('cart.processPayment', $transaction->id) }}" method="POST">
    @csrf
    <input type="radio" name="payment_method" value="DANA"   required>
    <input type="radio" name="payment_method" value="Gopay"  required>
    <input type="radio" name="payment_method" value="BCA"    required>
    <button type="submit">Bayar Sekarang</button>
</form>
```

---

## 4️⃣ FILE HANDLING

**Yang dijelaskan:** Upload foto profil dan foto produk.

> *"File handling ada di dua tempat: upload foto profil user, dan upload foto produk oleh admin."*

**Upload Foto Profil — `ProfileController.php`:**
> *"Waktu user upload foto profil, kita validasi ekstensinya dulu, generate nama unik biar tidak bentrok, lalu simpan ke folder `public/uploads`:"*
```php
$ekstensiDiizinkan = ['jpg', 'png'];
$ekstensi = strtolower($file->getClientOriginalExtension());

if (!in_array($ekstensi, $ekstensiDiizinkan)) {
    return back()->with('pesan_error', 'Hanya .jpg atau .png yang diperbolehkan!');
}

$namaFileBaru = 'img_' . time() . '_' . $namaBersih . '.' . $ekstensi;
$file->move(public_path('uploads'), $namaFileBaru);

// Simpan ke database dan session
$user->profile_pic = $namaFileBaru;
$user->save();
session(['profile_pic' => $namaFileBaru]);
```

> *"Ada preview foto sebelum submit — ini pakai `FileReader` API di JavaScript:"*
```javascript
// Di profile/index.blade.php
function previewAndSubmit(input) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewWrap').style.display = 'flex';
    };
    reader.readAsDataURL(input.files[0]);
}
```

**Upload Foto Produk** oleh admin ada di `StokBarangController` dengan pola yang sama — validasi, rename, move ke `public/uploads/products/`. Hasilnya ditampilkan di katalog:
```blade
@if($product->image)
    <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
@else
    <span>⛺</span> {{-- Emoji fallback jika tidak ada foto --}}
@endif
```

---

## 5️⃣ PHP STATE

**Yang dijelaskan:** Bagaimana data berpindah antar halaman tanpa hilang.

> *"PHP State adalah cara kita menjaga data tetap ada saat berpindah halaman."*

**Contoh 1 — Item yang dipilih di Cart diteruskan ke Checkout:**
> *"Saat user klik checkout, ID item yang dicentang dikirim via GET. Di halaman checkout, ID-nya disimpan sebagai hidden input supaya saat form checkout di-submit, data itemnya tetap ikut:"*
```php
// CartController@checkout — terima dari GET
$selectedIds = $request->input('items', []);
return view('cart.checkout', compact('cartItems', 'selectedIds'));
```
```html
<!-- checkout.blade.php — simpan ke hidden input -->
@foreach($selectedIds as $id)
    <input type="hidden" name="items[]" value="{{ $id }}">
@endforeach
```

**Contoh 2 — Flash Message:**
> *"Flash message adalah state sementara — data yang hanya hidup untuk satu request. Misalnya pesan sukses setelah bayar:"*
```php
// CartController
return redirect()->route('cart.paymentSuccess', $transaction->id)
    ->with('success', 'Pembayaran berhasil!');
```
```blade
{{-- Di view, cek session flash --}}
@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
```

**Contoh 3 — Data user dipertahankan di semua halaman:**
> *"Nama dan foto profil user muncul di topbar semua halaman karena disimpan sebagai PHP State via Session. Akan saya jelaskan lebih detail di bagian berikutnya."*

---

## 6️⃣ SESSION & COOKIES

**Yang dijelaskan:** Session dipakai untuk login state dan data user aktif.

> *"Session adalah tempat menyimpan data user selama mereka masih login. Ini yang paling banyak kita pakai."*

**Set Session saat Login — `AuthController.php`:**
> *"Waktu user berhasil login, kita simpan semua info penting ke session:"*
```php
session([
    'user_id'     => $user->id,
    'username'    => $user->username,
    'name'        => $user->name,
    'email'       => $user->email,
    'role'        => $user->role,
    'profile_pic' => $user->profile_pic,
]);
```

**Baca Session di Controller — `TransaksiController.php`:**
> *"Di setiap controller, kita baca `user_id` dari session untuk ambil data yang sesuai. Jadi user hanya bisa lihat transaksi miliknya sendiri:"*
```php
public function index()
{
    $userId = session('user_id'); // Baca dari session
    
    $transactions = Transaction::where('user_id', $userId) // Filter by user
        ->with(['items.product'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('transaksi.index', compact('transactions'));
}
```

**Middleware sebagai penjaga Session — `AuthMiddleware.php`:**
> *"Kita punya middleware yang ngecek session sebelum user bisa akses halaman apapun yang butuh login:"*
```php
public function handle(Request $request, Closure $next): Response
{
    if (!$request->session()->has('username')) {
        return redirect()->route('login')
            ->with('auth_error', 'Silakan login terlebih dahulu.');
    }
    return $next($request);
}
```

> *"Middleware ini dipasang di routes, jadi semua route di dalam grup ini otomatis terlindungi:"*
```php
// web.php
Route::middleware('auth.check')->group(function () {
    Route::get('/katalog', ...);
    Route::get('/transaksi', ...);
    Route::get('/cart', ...);
    // dst...
});
```

**Session di View — tampilan foto profil:**
> *"Di view, session langsung bisa dipakai tanpa perlu kirim dari controller:"*
```blade
@if(session('profile_pic'))
    <img src="{{ asset('uploads/' . session('profile_pic')) }}" alt="Profile">
@endif
```

**Destroy Session saat Logout:**
```php
public function logout(Request $request)
{
    $request->session()->flush(); // Hapus semua session
    return redirect()->route('login');
}
```

> *"Untuk Cookies, Laravel otomatis menggunakan cookie `laravel_session` untuk melacak session ID di browser. Kita tidak perlu set cookie manual karena Laravel sudah handle ini."*

---

## 7️⃣ DATABASE

**Yang dijelaskan:** Struktur tabel dan relasi antar tabel dalam alur transaksi.

> *"Database kita pakai SQLite dengan 5 tabel utama yang saling berelasi. Saya fokus ke bagian transaksi."*

**Struktur Tabel — via Migration:**

> *"Semua struktur tabel dibuat dengan Migration. Ini tabel `transactions`:"*
```php
// create_transactions_table.php
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->string('invoice_number')->unique(); // Contoh: TRX-20260529-A1B2C3
    $table->string('status')->default('Menunggu Pembayaran');
    $table->text('pickup_location')->nullable();
    $table->string('payment_method')->nullable();
    $table->integer('subtotal');
    $table->integer('discount')->default(0);
    $table->integer('total');
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

> *"Dan tabel `transaction_items` untuk detail barang per transaksi:"*
```php
Schema::create('transaction_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('transaction_id');
    $table->string('product_id');
    $table->integer('qty');
    $table->integer('price');        // Harga snapshot saat transaksi
    $table->integer('duration_days');
    $table->date('start_date');
    $table->date('end_date');
    $table->timestamps();

    $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
});
```

**Model & Relasi — Eloquent ORM:**
> *"Relasi antar tabel kita definisikan di Model. `Transaction` punya banyak `TransactionItem`, dan setiap item punya satu `Product`:"*
```php
// Transaction.php
public function items() {
    return $this->hasMany(TransactionItem::class); // 1 transaksi → banyak item
}

// TransactionItem.php
public function product() {
    return $this->belongsTo(Product::class); // 1 item → 1 produk
}
```

**Insert Data Transaksi — `CartController@processCheckout`:**
> *"Saat user konfirmasi checkout, kita insert ke dua tabel sekaligus. Dulu buat transaksi headernya dulu, lalu loop untuk buat detail itemnya:"*
```php
// 1. Buat header transaksi
$transaction = Transaction::create([
    'user_id'        => $userId,
    'invoice_number' => 'TRX-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3))),
    'status'         => 'Menunggu Pembayaran',
    'pickup_location'=> $pickupLocation,
    'subtotal'       => $subtotal,
    'discount'       => 0,
    'total'          => $subtotal,
]);

// 2. Loop setiap item untuk buat detail
foreach ($cartItems as $item) {
    TransactionItem::create([
        'transaction_id' => $transaction->id,  // Foreign key ke transaksi tadi
        'product_id'     => $item->product_id,
        'qty'            => $item->qty,
        'price'          => $item->product->price,
        'duration_days'  => $durationDays,
        'start_date'     => $startDate,
        'end_date'       => $endDate,
    ]);

    $item->delete(); // Hapus dari cart setelah jadi transaksi
}
```

**Baca Data dengan Eager Loading:**
> *"Untuk tampilkan daftar transaksi, kita pakai `with()` supaya data product langsung ikut di-load sekaligus — ini namanya Eager Loading, lebih efisien dari query satu-satu:"*
```php
$transactions = Transaction::where('user_id', $userId)
    ->with(['items.product'])  // Sekaligus load items dan productnya
    ->orderBy('created_at', 'desc')
    ->get();
```

**Update Stok Produk saat Bayar:**
> *"Setelah user bayar, stok produk langsung berkurang dan statusnya diupdate otomatis:"*
```php
// CartController@processPayment
$product->stock = $product->stock - $item->qty;

if ($product->stock <= 0)     $product->status = 'Habis';
elseif ($product->stock <= 3) $product->status = 'Hampir Habis';
else                          $product->status = 'Tersedia';

$product->save();

$transaction->update(['status' => 'Lunas', 'payment_method' => $paymentMethod]);
```

---

## 8️⃣ MVC / FRAMEWORK

**Yang dijelaskan:** Bagaimana Laravel memisahkan logika jadi Model, View, Controller.

> *"Web ini dibangun dengan Laravel — framework PHP yang menerapkan pola MVC: Model, View, Controller."*

**Diagram alur MVC dalam transaksi:**
```
USER → Route → Controller → Model → Database
                    ↓
                  View ← Data
```

**MODEL** — Representasi data dan relasi:
> *"Model adalah representasi tabel database. `Cart`, `Transaction`, `TransactionItem`, `Product` — semuanya punya Model masing-masing. Di sini kita definisikan relasi dan kolom yang boleh diisi:"*
```php
// Cart.php
class Cart extends Model {
    protected $fillable = ['user_id', 'product_id', 'qty', 'duration_days'];

    public function product() {
        return $this->belongsTo(Product::class); // relasi ke produk
    }
}
```

**VIEW** — Tampilan untuk user:
> *"View adalah file `.blade.php` yang menampilkan data ke user. Blade adalah template engine Laravel yang memungkinkan kita mix PHP dan HTML dengan syntax bersih:"*
```blade
{{-- transaksi/index.blade.php --}}
@foreach($transactions as $trx)
    <div class="trx-card" data-status="{{ $trx->status }}">
        <h3>{{ $trx->items->first()->product->name }}</h3>
        <span>Invoice: {{ $trx->invoice_number }}</span>
        <span>Rp {{ number_format($trx->total, 0, ',', '.') }}</span>
    </div>
@endforeach
```

**CONTROLLER** — Logika bisnis penghubung Model dan View:
> *"Controller berisi semua logika bisnis. `CartController` misalnya punya 8 method yang masing-masing menangani satu aksi:"*
```php
class CartController extends Controller {
    public function index()          { /* tampilkan cart */ }
    public function add()            { /* tambah ke cart */ }
    public function update()         { /* ubah qty — sekarang support AJAX */ }
    public function delete()         { /* hapus item — sekarang support AJAX */ }
    public function checkout()       { /* halaman konfirmasi sewa */ }
    public function processCheckout(){ /* buat Transaction & TransactionItem */ }
    public function payment()        { /* halaman pilih metode bayar */ }
    public function processPayment() { /* proses bayar, update stok */ }
}
```

**ROUTE** — Jembatan URL ke Controller:
> *"Routes di `web.php` menghubungkan URL ke method controller yang tepat. Kita juga pakai Route Grouping dengan Middleware:"*
```php
Route::middleware('auth.check')->group(function () {
    Route::get('/cart',                    [CartController::class, 'index']);
    Route::post('/cart/add/{product}',     [CartController::class, 'add']);
    Route::post('/cart/update/{cart}',     [CartController::class, 'update']);
    Route::delete('/cart/delete/{cart}',   [CartController::class, 'delete']);
    Route::get('/checkout',                [CartController::class, 'checkout']);
    Route::post('/checkout',               [CartController::class, 'processCheckout']);
    Route::get('/payment/{transaction}',   [CartController::class, 'payment']);
    Route::post('/payment/{transaction}',  [CartController::class, 'processPayment']);
});
```

> *"Dengan MVC, kode jadi terpisah dan terorganisir. Kalau mau ubah tampilan, cukup ubah View. Kalau mau ubah logika bisnis, cukup ubah Controller. Kalau mau ubah struktur data, cukup ubah Model dan Migration."*

---

## 🎬 PENUTUP

> *"Jadi itulah bagian yang saya kerjakan. Untuk merangkum:"*
>
> - **Responsive** → Grid CSS + media query + JS resize handler
> - **HTML/CSS/JS/PHP/AJAX** → Semua dipakai bersama dalam satu alur transaksi
> - **Form** → 3 form dalam alur: pilih item, konfirmasi sewa, pilih pembayaran
> - **File Handling** → Upload foto profil & produk dengan validasi dan rename
> - **PHP State** → Hidden input untuk teruskan data antar halaman + flash message
> - **Session** → Simpan data login, baca di controller & view, middleware proteksi
> - **Database** → 5 tabel relasional, Migration, Eloquent ORM, Eager Loading
> - **MVC/Laravel** → Model-View-Controller terpisah dengan routing middleware
>
> *"Silakan kalau ada pertanyaan."*

---

## 💡 TIPS PRESENTASI

| Situasi | Tips |
|---|---|
| Ditanya "kenapa pakai Laravel?" | *"Karena Laravel sudah menyediakan banyak fitur built-in seperti routing, Eloquent ORM, session, middleware — jadi kita fokus ke logika bisnis, bukan setup dari nol."* |
| Ditanya "bedanya Session dan Cookie?" | *"Session disimpan di server, lebih aman untuk data sensitif seperti user_id. Cookie disimpan di browser user. Laravel pakai cookie hanya untuk nyimpan ID session-nya, data aslinya tetap di server."* |
| Ditanya "CSRF itu apa?" | *"CSRF token adalah kode acak yang Laravel generate untuk setiap form. Tujuannya untuk memastikan request benar-benar datang dari form kita, bukan dari website jahat yang nyamar."* |
| Ditanya "kenapa pakai Eager Loading?" | *"Supaya tidak terjadi N+1 query problem. Tanpa `with()`, setiap transaksi akan query database lagi untuk ambil itemnya. Dengan `with(['items.product'])`, semuanya selesai dalam 3 query."* |
| Ditanya "apa bedanya `hasMany` dan `belongsTo`?" | *"1 Transaction punya banyak TransactionItem — itu `hasMany`. Sebaliknya, 1 TransactionItem milik 1 Transaction — itu `belongsTo`."* |


---

# ⚡ SCRIPT VERSI RINGKAS (~2-3 Menit)
> Ini yang dibacain pas presentasi. Hapal alurnya, tangan gerak di web.

---

## 🎬 PEMBUKA (10 detik)
> *"Bagian saya ada dua — halaman profil, dan alur transaksi dari keranjang sampai pembayaran. Langsung demo."*

---

## 👤 DEMO PROFIL (~45 detik)

**[Klik menu Profil Saya di sidebar]**
> *"Ini halaman Edit Profil. Layout dua kolom — pakai **CSS Grid responsive**. Di kanan ada progress ring yang ngitung persentase kelengkapan profil, itu dihitung di **PHP** server-side."*

**[Klik tombol Edit di Informasi Pribadi]**
> *"Setiap section ada toggle edit. Ini **Form** POST — update nama, email, telepon langsung ke tabel `users` di **Database**."*

**[Klik Cancel → Klik avatar atau tombol 'Upload foto baru']**
> *"Untuk foto, ini **File Handling**. Pilih file, langsung ada preview — itu **JavaScript** `FileReader` API, belum ke server."*

**[Pilih foto → preview muncul → klik Simpan Foto]**
> *"Setelah simpan, PHP validasi ekstensi, rename pakai `time()`, simpan ke folder `uploads/`, update **database**, dan update **Session** `profile_pic` — makanya foto langsung berubah di topbar tanpa login ulang."*

---

## 🛒 DEMO TRANSAKSI (~1 menit 30 detik)

**[Buka halaman Katalog]**
> *"Katalog pakai **CSS Grid responsive**, auto menyesuaikan layar. Saya klik tambah ke keranjang..."*
> *"Badge ini langsung update tanpa reload — itu **AJAX** pakai `fetch()`, server balik **JSON**."*

**[Buka halaman Cart]**
> *"Tombol `+` `−` juga **AJAX** — qty dan subtotal langsung berubah di DOM."*
> *"Cart ini **Form** GET — user centang item, klik checkout. Data user dari **Session** `user_id`, jadi yang tampil cuma keranjang milik dia."*

**[Klik Lanjut ke Rincian Sewa]**
> *"Di checkout, ganti tanggal → total langsung hitung ulang, itu **JavaScript** murni."*
> *"ID item diteruskan lewat hidden input — contoh **PHP State** antar halaman. Form pakai `@csrf` untuk keamanan."*

**[Submit → Halaman Pembayaran]**
> *"Controller `processCheckout` buat record di tabel `transactions` dan loop buat `transaction_items` — ini **MVC Laravel** dan **Database**."*

**[Pilih metode bayar → Bayar Sekarang]**
> *"Stok produk otomatis berkurang, status transaksi jadi Lunas."*

**[Buka halaman Transaksi]**
> *"Riwayat pakai **Eager Loading** `with(['items.product'])` — efisien, satu query buat semua relasi."*

---

## 🎬 PENUTUP (5 detik)
> *"Semua 8 konsep ada di bagian saya — dari profil sampai transaksi selesai. Sekian."*

---

## 💡 TIPS JAWAB PERTANYAAN DADAKAN

| Pertanyaan | Jawaban Singkat |
|---|---|
| CSRF itu apa? | Token acak di setiap form, cegah request palsu dari luar |
| Session vs Cookie? | Session di server (aman), Cookie di browser. Laravel pakai cookie hanya buat nyimpen ID session-nya |
| Eager Loading itu apa? | Pakai `with()` supaya relasi diload sekaligus, hindari query berulang |
| Kenapa pakai Laravel? | Sudah ada routing, ORM, session, middleware — tinggal fokus ke logika bisnis |