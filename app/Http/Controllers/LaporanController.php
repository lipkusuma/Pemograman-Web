<?php

namespace App\Http\Controllers;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = [
            ['id' => 'TRX001', 'tanggal' => '2025-09-10', 'pelanggan' => 'Budi Santoso',    'produk' => 'Tenda Ultralight 2P',  'qty' => 1, 'total' => 850000,  'status' => 'Selesai'],
            ['id' => 'TRX002', 'tanggal' => '2025-09-10', 'pelanggan' => 'Ani Wulandari',   'produk' => 'Carrier 60L Pro',       'qty' => 1, 'total' => 1200000, 'status' => 'Selesai'],
            ['id' => 'TRX003', 'tanggal' => '2025-09-09', 'pelanggan' => 'Riko Pratama',    'produk' => 'Headlamp LED 1000lm',   'qty' => 2, 'total' => 570000,  'status' => 'Selesai'],
            ['id' => 'TRX004', 'tanggal' => '2025-09-09', 'pelanggan' => 'Dewi Lestari',    'produk' => 'Sleeping Bag -5°C',     'qty' => 1, 'total' => 450000,  'status' => 'Pending'],
            ['id' => 'TRX005', 'tanggal' => '2025-09-08', 'pelanggan' => 'Andi Firmansyah', 'produk' => 'Trekking Pole Carbon',  'qty' => 1, 'total' => 550000,  'status' => 'Selesai'],
            ['id' => 'TRX006', 'tanggal' => '2025-09-08', 'pelanggan' => 'Sari Indah',      'produk' => 'Matras Foam Premium',   'qty' => 2, 'total' => 640000,  'status' => 'Dibatalkan'],
            ['id' => 'TRX007', 'tanggal' => '2025-09-07', 'pelanggan' => 'Joko Widodo',     'produk' => 'Nesting Set Aluminium', 'qty' => 3, 'total' => 585000,  'status' => 'Selesai'],
        ];

        $totalTransaksi     = count($laporan);
        $totalPendapatan    = array_sum(array_column(array_filter($laporan, fn($l) => $l['status'] === 'Selesai'), 'total'));
        $totalProdukTerjual = array_sum(array_column(array_filter($laporan, fn($l) => $l['status'] === 'Selesai'), 'qty'));
        $totalPelanggan     = count(array_unique(array_column($laporan, 'pelanggan')));

        return view('laporan.index', compact(
            'laporan', 'totalTransaksi', 'totalPendapatan', 'totalProdukTerjual', 'totalPelanggan'
        ));
    }
}
