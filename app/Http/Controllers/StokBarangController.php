<?php

namespace App\Http\Controllers;

class StokBarangController extends Controller
{
    public function index()
    {
        $stokBarang = [
            ['id' => 'BRG001', 'nama' => 'Tenda Ultralight 2P',   'kategori' => 'Tenda',        'stok' => 15, 'harga' => 850000,  'status' => 'Tersedia'],
            ['id' => 'BRG002', 'nama' => 'Carrier 60L Pro',        'kategori' => 'Tas',          'stok' => 8,  'harga' => 1200000, 'status' => 'Tersedia'],
            ['id' => 'BRG003', 'nama' => 'Sleeping Bag -5°C',      'kategori' => 'Alat Pribadi', 'stok' => 3,  'harga' => 450000,  'status' => 'Hampir Habis'],
            ['id' => 'BRG004', 'nama' => 'Kompor Portable Gas',    'kategori' => 'Alat Masak',   'stok' => 0,  'harga' => 175000,  'status' => 'Habis'],
            ['id' => 'BRG005', 'nama' => 'Headlamp LED 1000lm',    'kategori' => 'Penerangan',   'stok' => 22, 'harga' => 285000,  'status' => 'Tersedia'],
            ['id' => 'BRG006', 'nama' => 'Matras Foam Premium',    'kategori' => 'Alat Pribadi', 'stok' => 5,  'harga' => 320000,  'status' => 'Tersedia'],
            ['id' => 'BRG007', 'nama' => 'Nesting Set Aluminium',  'kategori' => 'Alat Masak',   'stok' => 2,  'harga' => 195000,  'status' => 'Hampir Habis'],
            ['id' => 'BRG008', 'nama' => 'Trekking Pole Carbon',   'kategori' => 'Alat Pribadi', 'stok' => 12, 'harga' => 550000,  'status' => 'Tersedia'],
        ];

        $totalBarang    = count($stokBarang);
        $totalStok      = array_sum(array_column($stokBarang, 'stok'));
        $habis          = count(array_filter($stokBarang, fn($b) => $b['status'] === 'Habis'));
        $hampirHabis    = count(array_filter($stokBarang, fn($b) => $b['status'] === 'Hampir Habis'));

        return view('stok_barang.index', compact(
            'stokBarang', 'totalBarang', 'totalStok', 'habis', 'hampirHabis'
        ));
    }
}
