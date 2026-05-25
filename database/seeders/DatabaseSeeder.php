<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Disable foreign key checks for sqlite truncate
        Schema::disableForeignKeyConstraints();

        // Hapus data lama jika ada (agar tidak duplikat saat re-seed)
        User::where("username", "admin")->delete();
        User::where("username", "user")->delete();
        Product::truncate();

        Schema::enableForeignKeyConstraints();

        // Buat akun admin default
        User::create([
            "name" => "Administrator",
            "username" => "admin",
            "email" => "admin@gmail.com",
            "phone" => "081234567890",
            "password" => Hash::make("admin123"),
            "role" => "admin",
        ]);

        // Buat akun user default
        User::create([
            "name" => "Customer Biasa",
            "username" => "user",
            "email" => "user@gmail.com",
            "phone" => "081234567891",
            "password" => Hash::make("user123"),
            "role" => "user",
        ]);

        // Seed produk default
        $products = [
            ['id' => 'BRG001', 'name' => 'Tenda Ultralight 2P',   'category' => 'Tenda',        'stock' => 15, 'price' => 850000,  'status' => 'Tersedia'],
            ['id' => 'BRG002', 'name' => 'Carrier 60L Pro',        'category' => 'Tas',          'stock' => 8,  'price' => 1200000, 'status' => 'Tersedia'],
            ['id' => 'BRG003', 'name' => 'Sleeping Bag -5°C',      'category' => 'Alat Pribadi', 'stock' => 3,  'price' => 450000,  'status' => 'Hampir Habis'],
            ['id' => 'BRG004', 'name' => 'Kompor Portable Gas',    'category' => 'Alat Masak',   'stock' => 0,  'price' => 175000,  'status' => 'Habis'],
            ['id' => 'BRG005', 'name' => 'Headlamp LED 1000lm',    'category' => 'Penerangan',   'stock' => 22, 'price' => 285000,  'status' => 'Tersedia'],
            ['id' => 'BRG006', 'name' => 'Matras Foam Premium',    'category' => 'Alat Pribadi', 'stock' => 5,  'price' => 320000,  'status' => 'Tersedia'],
            ['id' => 'BRG007', 'name' => 'Nesting Set Aluminium',  'category' => 'Alat Masak',   'stock' => 2,  'price' => 195000,  'status' => 'Hampir Habis'],
            ['id' => 'BRG008', 'name' => 'Trekking Pole Carbon',   'category' => 'Alat Pribadi', 'stock' => 12, 'price' => 550000,  'status' => 'Tersedia'],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
