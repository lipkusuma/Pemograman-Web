<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CartController extends Controller
{
    /** Display the user's cart */
    public function index()
    {
        $userId = session('user_id');
        $carts = Cart::where('user_id', $userId)->with('product')->get();

        return view('cart.index', compact('carts'));
    }

    /** Add a product to the cart */
    public function add(Request $request, Product $product)
    {
        $userId = session('user_id');

        // Check if product is in stock
        if ($product->stock <= 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk ini sedang tidak tersedia (stok habis).',
                ], 422);
            }
            return back()->with('error', 'Produk ini sedang tidak tersedia (stok habis).');
        }

        // Check if item already exists in user's cart
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Increase qty if stock allows
            if ($cartItem->qty < $product->stock) {
                $cartItem->increment('qty');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah di keranjang sudah mencapai batas stok maksimum.',
                    ], 422);
                }
                return redirect()->route('cart.index')->with('warning', 'Jumlah di keranjang sudah mencapai batas stok maksimum.');
            }
        } else {
            // Create new cart item
            Cart::create([
                'user_id'      => $userId,
                'product_id'   => $product->id,
                'qty'          => 1,
                'duration_days' => 1,
            ]);
        }

        if ($request->expectsJson()) {
            $cartCount = Cart::where('user_id', $userId)->sum('qty');
            return response()->json([
                'success'    => true,
                'message'    => 'Produk berhasil ditambahkan ke keranjang.',
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /** Update cart item quantity */
    public function update(Request $request, Cart $cart)
    {
        $userId = session('user_id');

        if ($cart->user_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $action  = $request->input('action');
        $product = $cart->product;
        $deleted = false;

        if ($action === 'increase') {
            if ($cart->qty < $product->stock) {
                $cart->increment('qty');
                $cart->refresh();
                $message = 'Jumlah berhasil ditambahkan.';
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah tidak bisa melebihi stok yang tersedia.',
                    ], 422);
                }
                return back()->with('warning', 'Jumlah tidak bisa melebihi stok yang tersedia.');
            }
        } elseif ($action === 'decrease') {
            if ($cart->qty > 1) {
                $cart->decrement('qty');
                $cart->refresh();
                $message = 'Jumlah berhasil dikurangi.';
            } else {
                $cart->delete();
                $deleted = true;
                $message = 'Item berhasil dihapus dari keranjang.';
            }
        } else {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aksi tidak valid.',
                ], 422);
            }
            return back();
        }

        if ($request->expectsJson()) {
            $newQty      = $deleted ? 0 : $cart->qty;
            $newSubtotal = $deleted ? 'Rp 0' : 'Rp ' . number_format($product->price * $newQty, 0, ',', '.');
            $cartCount   = Cart::where('user_id', $userId)->sum('qty');

            return response()->json([
                'success'     => true,
                'new_qty'     => $newQty,
                'new_subtotal' => $newSubtotal,
                'cart_count'  => $cartCount,
                'deleted'     => $deleted,
                'message'     => $message,
            ]);
        }

        if ($deleted) {
            return back()->with('success', $message);
        }

        return back();
    }

    /** Remove item from cart */
    public function delete(Request $request, Cart $cart)
    {
        $userId = session('user_id');

        if ($cart->user_id != $userId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.',
                ], 403);
            }
            return back()->with('error', 'Unauthorized access.');
        }

        $cart->delete();

        if ($request->expectsJson()) {
            $cartCount = Cart::where('user_id', $userId)->sum('qty');
            return response()->json([
                'success'    => true,
                'message'    => 'Produk berhasil dihapus dari keranjang.',
                'cart_count' => $cartCount,
            ]);
        }

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /** Checkout page for selected cart items */
    public function checkout(Request $request)
    {
        $userId = session('user_id');
        $selectedIds = $request->input('items', []);

        if (empty($selectedIds)) {
            return redirect()->route('cart.index')->with('warning', 'Pilih minimal satu produk untuk melakukan sewa.');
        }

        $cartItems = Cart::where('user_id', $userId)
            ->whereIn('id', $selectedIds)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan.');
        }

        return view('cart.checkout', compact('cartItems', 'selectedIds'));
    }

    /** Process checkout and create transaction (Pending Payment) */
    public function processCheckout(Request $request)
    {
        $userId = session('user_id');
        $selectedIds = $request->input('items', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $pickupLocation = $request->input('pickup_location');

        if (empty($selectedIds)) {
            return redirect()->route('cart.index')->with('warning', 'Pilih minimal satu produk untuk melakukan sewa.');
        }

        if (empty($startDate) || empty($endDate) || empty($pickupLocation)) {
            return back()->withInput()->with('error', 'Semua kolom wajib diisi (Tanggal sewa & Lokasi pengambilan).');
        }

        // Calculate rental duration in days
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $durationDays = $start->diffInDays($end);

        // Rental must be at least 1 day
        if ($durationDays <= 0) {
            $durationDays = 1;
        }

        // Retrieve selected cart items
        $cartItems = Cart::where('user_id', $userId)
            ->whereIn('id', $selectedIds)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan.');
        }

        // Calculate Subtotal & Total
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->qty * $durationDays;
        }

        $discount = 0; // Default discount
        $total = $subtotal - $discount;

        // Generate Unique Invoice Number (TRX-YYYYMMDD-RAND)
        $invoiceNumber = 'TRX-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

        // Create transaction with status 'Menunggu Pembayaran'
        $transaction = Transaction::create([
            'user_id'         => $userId,
            'invoice_number'  => $invoiceNumber,
            'status'          => 'Menunggu Pembayaran',
            'pickup_location' => $pickupLocation,
            'subtotal'        => $subtotal,
            'discount'        => $discount,
            'total'           => $total,
        ]);

        // Create Transaction Items and remove from cart
        foreach ($cartItems as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $item->product_id,
                'qty'            => $item->qty,
                'price'          => $item->product->price,
                'duration_days'  => $durationDays,
                'start_date'     => $startDate,
                'end_date'       => $endDate,
            ]);

            // Delete from cart
            $item->delete();
        }

        return redirect()->route('cart.payment', $transaction->id);
    }

    /**
     * API-friendly checkout for interface testing (no auth, no CSRF required when excluded)
     * Expects JSON body with: user_id, pickup_location, items: [{product_id, qty, price?, duration_days?, start_date?, end_date?}, ...]
     */
    public function processCheckoutApi(Request $request)
    {
        $data = $request->all();

        $userId = $data['user_id'] ?? null;
        $pickupLocation = $data['pickup_location'] ?? null;
        $items = $data['items'] ?? [];

        if (empty($userId) || empty($items) || !is_array($items)) {
            return response()->json(['success' => false, 'message' => 'Missing user_id or items'], 422);
        }

        // Validate that the provided user exists to satisfy FK constraint on transactions.user_id
        $userExists = User::where('id', $userId)->exists();
        if (! $userExists) {
            return response()->json([
                'success' => false,
                'message' => 'User not found. Pastikan `user_id` mengacu pada user yang ada di database.'
            ], 422);
        }

        // Calculate subtotal and create transaction
        $subtotal = 0;
        foreach ($items as $it) {
            $qty = isset($it['qty']) ? (int)$it['qty'] : 1;
            $price = isset($it['price']) ? (float)$it['price'] : 0;
            $duration = isset($it['duration_days']) ? (int)$it['duration_days'] : 1;
            $subtotal += $price * $qty * max(1, $duration);
        }

        $discount = $data['discount'] ?? 0;
        $total = $subtotal - $discount;

        $invoiceNumber = 'TRX-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

        $transaction = Transaction::create([
            'user_id' => $userId,
            'invoice_number' => $invoiceNumber,
            'status' => 'Menunggu Pembayaran',
            'pickup_location' => $pickupLocation,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ]);

        foreach ($items as $it) {
            $qty = isset($it['qty']) ? (int) $it['qty'] : 1;
            $price = isset($it['price']) ? (float) $it['price'] : 0;
            $duration = isset($it['duration_days']) ? (int) $it['duration_days'] : 1;

            // Ensure start/end dates are set (DB requires non-null dates)
            $start_date = isset($it['start_date']) && !empty($it['start_date']) ? $it['start_date'] : Carbon::today()->toDateString();
            $end_date = isset($it['end_date']) && !empty($it['end_date']) ? $it['end_date'] : Carbon::parse($start_date)->addDays($duration)->toDateString();

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => isset($it['product_id']) ? (int) $it['product_id'] : null,
                'qty' => $qty,
                'price' => $price,
                'duration_days' => $duration,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);
        }

        return response()->json([
            'success' => true,
            'transaction_id' => $transaction->id,
            'invoice_number' => $invoiceNumber,
            'total' => $total,
        ], 201);
    }

    /** Display payment method selection page */
    public function payment(Transaction $transaction)
    {
        $userId = session('user_id');
        if ($transaction->user_id != $userId) {
            return redirect()->route('katalog')->with('error', 'Akses ditolak.');
        }

        if ($transaction->status !== 'Menunggu Pembayaran') {
            return redirect()->route('transaksi')->with('info', 'Transaksi ini sudah selesai atau dibatalkan.');
        }

        return view('cart.payment', compact('transaction'));
    }

    /** Process simulated payment */
    public function processPayment(Request $request, Transaction $transaction)
    {
        $userId = session('user_id');
        if ($transaction->user_id != $userId) {
            return redirect()->route('katalog')->with('error', 'Akses ditolak.');
        }

        $paymentMethod = $request->input('payment_method');
        if (empty($paymentMethod)) {
            return back()->with('error', 'Silakan pilih metode pembayaran terlebih dahulu.');
        }

        // Verify and update product stocks
        $transactionItems = TransactionItem::where('transaction_id', $transaction->id)->with('product')->get();
        foreach ($transactionItems as $item) {
            $product = $item->product;
            if ($product->stock < $item->qty) {
                // If stock is not enough, cancel payment or alert
                return back()->with('error', 'Stok untuk barang "' . $product->name . '" tidak mencukupi untuk disewa.');
            }
        }

        // Reduce stock and update status for each product
        foreach ($transactionItems as $item) {
            $product = $item->product;
            $newStock = $product->stock - $item->qty;
            $product->stock = $newStock;

            if ($newStock <= 0) {
                $product->status = 'Habis';
            } elseif ($newStock <= 3) {
                $product->status = 'Hampir Habis';
            } else {
                $product->status = 'Tersedia';
            }
            $product->save();
        }

        // Complete the payment
        $transaction->update([
            'status'         => 'Lunas',
            'payment_method' => $paymentMethod,
        ]);

        return redirect()->route('cart.paymentSuccess', $transaction->id);
    }

    /** Display payment success screen */
    public function paymentSuccess(Transaction $transaction)
    {
        $userId = session('user_id');
        if ($transaction->user_id != $userId) {
            return redirect()->route('katalog')->with('error', 'Akses ditolak.');
        }

        return view('cart.success', compact('transaction'));
    }
}
