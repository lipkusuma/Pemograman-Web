<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Hapus data lama jika ada (agar tidak duplikat saat re-seed)
        User::where("username", "admin")->delete();

        // Buat akun admin default
        User::create([
            "name" => "Administrator",
            "username" => "admin",
            "email" => "admin@gmail.com",
            "phone" => "081234567890",
            "password" => Hash::make("admin123"),
            "role" => "admin",
        ]);
    }
}
