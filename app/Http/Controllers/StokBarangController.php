<?php

namespace App\Http\Controllers;

use App\Models\Product;

class StokBarangController extends Controller
{
    public function index()
    {
        $products = Product::all();

        // Map column names to fit the existing Blade view which expects 'nama', 'kategori', 'stok', 'harga'
        $stokBarang = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'nama' => $product->name,
                'kategori' => $product->category,
                'stok' => $product->stock,
                'harga' => $product->price,
                'status' => $product->status,
            ];
        })->toArray();

        $totalBarang    = count($stokBarang);
        $totalStok      = array_sum(array_column($stokBarang, 'stok'));
        $habis          = count(array_filter($stokBarang, fn($b) => $b['status'] === 'Habis'));
        $hampirHabis    = count(array_filter($stokBarang, fn($b) => $b['status'] === 'Hampir Habis'));

        return view('stok_barang.index', compact(
            'stokBarang', 'totalBarang', 'totalStok', 'habis', 'hampirHabis'
        ));
    }
}
