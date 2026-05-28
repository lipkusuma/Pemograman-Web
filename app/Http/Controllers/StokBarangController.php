<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StokBarangController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        $stokBarang = $products->map(function ($product) {
            return [
                'id'       => $product->id,
                'nama'     => $product->name,
                'kategori' => $product->category,
                'stok'     => $product->stock,
                'harga'    => $product->price,
                'status'   => $product->status,
                'image'    => $product->image,
            ];
        })->toArray();

        $totalBarang  = count($stokBarang);
        $totalStok    = array_sum(array_column($stokBarang, 'stok'));
        $habis        = count(array_filter($stokBarang, fn($b) => $b['status'] === 'Habis'));
        $hampirHabis  = count(array_filter($stokBarang, fn($b) => $b['status'] === 'Hampir Habis'));

        // Get unique categories for the form dropdown
        $categories = Product::distinct()->pluck('category')->sort()->values();

        return view('stok_barang.index', compact(
            'stokBarang', 'totalBarang', 'totalStok', 'habis', 'hampirHabis', 'categories'
        ));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id'       => 'required|string|max:20|unique:products,id',
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'stock'    => 'required|integer|min:0',
            'price'    => 'required|integer|min:0',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'id.required'       => 'Kode barang wajib diisi.',
            'id.unique'         => 'Kode barang sudah digunakan.',
            'name.required'     => 'Nama barang wajib diisi.',
            'category.required' => 'Kategori wajib diisi.',
            'stock.required'    => 'Stok wajib diisi.',
            'stock.min'         => 'Stok tidak boleh negatif.',
            'price.required'    => 'Harga wajib diisi.',
            'price.min'         => 'Harga tidak boleh negatif.',
            'image.image'       => 'File harus berupa gambar.',
            'image.max'         => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Determine status based on stock
        $stock = (int) $request->stock;
        if ($stock <= 0) {
            $status = 'Habis';
        } elseif ($stock <= 3) {
            $status = 'Hampir Habis';
        } else {
            $status = 'Tersedia';
        }

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/products'), $imageName);
        }

        Product::create([
            'id'       => $request->id,
            'name'     => $request->name,
            'category' => $request->category,
            'stock'    => $stock,
            'price'    => $request->price,
            'status'   => $status,
            'image'    => $imageName,
        ]);

        return redirect()->route('stok_barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Show the edit form for a product.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Product::distinct()->pluck('category')->sort()->values();

        return view('stok_barang.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'stock'    => 'required|integer|min:0',
            'price'    => 'required|integer|min:0',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'     => 'Nama barang wajib diisi.',
            'category.required' => 'Kategori wajib diisi.',
            'stock.required'    => 'Stok wajib diisi.',
            'stock.min'         => 'Stok tidak boleh negatif.',
            'price.required'    => 'Harga wajib diisi.',
            'price.min'         => 'Harga tidak boleh negatif.',
            'image.image'       => 'File harus berupa gambar.',
            'image.max'         => 'Ukuran gambar maksimal 2MB.',
        ]);

        $stock = (int) $request->stock;
        if ($stock <= 0) {
            $status = 'Habis';
        } elseif ($stock <= 3) {
            $status = 'Hampir Habis';
        } else {
            $status = 'Tersedia';
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
                unlink(public_path('uploads/products/' . $product->image));
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/products'), $imageName);
            $product->image = $imageName;
        }

        $product->update([
            'name'     => $request->name,
            'category' => $request->category,
            'stock'    => $stock,
            'price'    => $request->price,
            'status'   => $status,
            'image'    => $product->image,
        ]);

        return redirect()->route('stok_barang')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete image if exists
        if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
            unlink(public_path('uploads/products/' . $product->image));
        }

        $product->delete();

        return redirect()->route('stok_barang')->with('success', 'Barang berhasil dihapus.');
    }
}
