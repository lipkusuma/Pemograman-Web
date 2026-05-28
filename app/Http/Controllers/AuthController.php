<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ─── Login ────────────────────────────────────────────────────────────────

    /** Tampilkan form login */
    public function loginForm()
    {
        return view("auth.login");
    }

    /** Proses login */
    public function login(Request $request)
    {
        $username = trim($request->input("username", ""));
        $password = $request->input("password", "");

        // Validasi input tidak kosong
        if ($username === "" || $password === "") {
            return back()
                ->with("auth_error", "Username dan Password wajib diisi!")
                ->withInput($request->only("username"));
        }

        // Cari user berdasarkan username
        $user = User::where("username", $username)->first();

        // Cek apakah user ada & password cocok
        if (!$user || !Hash::check($password, $user->password)) {
            return back()
                ->with("auth_error", "Username atau Password salah!")
                ->withInput($request->only("username"));
        }

        // Set session login
        session([
            "user_id"     => $user->id,
            "username"    => $user->username,
            "name"        => $user->name,
            "email"       => $user->email,
            "role"        => $user->role,
            "profile_pic" => $user->profile_pic,
        ]);

        // Redirect berdasarkan role
        if ($user->role === "admin") {
            return redirect()
                ->route("dashboard")
                ->with("auth_success", "Selamat datang, " . $user->name . "!");
        }

        return redirect()
            ->route("katalog")
            ->with("auth_success", "Selamat datang, " . $user->name . "!");
    }

    // ─── Register ─────────────────────────────────────────────────────────────

    /** Tampilkan form register */
    public function registerForm()
    {
        return view("auth.register");
    }

    /** Proses register */
    public function register(Request $request)
    {
        $username = trim($request->input("username", ""));
        $email = trim($request->input("email", ""));
        $phone = trim($request->input("phone", ""));
        $password = $request->input("password", "");
        $confirmPassword = $request->input("confirm_password", "");

        // Validasi: semua field wajib diisi
        if (
            empty($username) ||
            empty($email) ||
            empty($phone) ||
            empty($password) ||
            empty($confirmPassword)
        ) {
            return back()
                ->with("auth_error", "Semua kolom wajib diisi!")
                ->withInput($request->only("username", "email", "phone"));
        }

        // Validasi format username
        if (!preg_match('/^[a-zA-Z0-9\_]{3,20}$/', $username)) {
            return back()
                ->with(
                    "auth_error",
                    "Username hanya boleh berisi huruf, angka, dan underscore (3-20 karakter)!",
                )
                ->withInput($request->only("username", "email", "phone"));
        }

        // Validasi format email
        if (!preg_match('/@gmail\.com$/i', $email)) {
            return back()
                ->with(
                    "auth_error",
                    "Format email harus menggunakan @gmail.com!",
                )
                ->withInput($request->only("username", "email", "phone"));
        }

        // Validasi format nomor HP
        if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            return back()
                ->with(
                    "auth_error",
                    "Nomor handphone harus berupa angka (10-15 digit)!",
                )
                ->withInput($request->only("username", "email", "phone"));
        }

        // Validasi konfirmasi password
        if ($password !== $confirmPassword) {
            return back()
                ->with(
                    "auth_error",
                    "Password dan Konfirmasi Password tidak cocok!",
                )
                ->withInput($request->only("username", "email", "phone"));
        }

        // Validasi panjang password minimal 6 karakter
        if (strlen($password) < 6) {
            return back()
                ->with("auth_error", "Password minimal 6 karakter!")
                ->withInput($request->only("username", "email", "phone"));
        }

        // Cek username sudah dipakai
        if (User::where("username", $username)->exists()) {
            return back()
                ->with(
                    "auth_error",
                    "Username sudah digunakan, coba username lain!",
                )
                ->withInput($request->only("email", "phone"));
        }

        // Cek email sudah dipakai
        if (User::where("email", $email)->exists()) {
            return back()
                ->with(
                    "auth_error",
                    "Email sudah terdaftar, gunakan email lain atau langsung login!",
                )
                ->withInput($request->only("username", "phone"));
        }

        // Simpan user baru ke database
        User::create([
            "name" => $username, // Gunakan username sebagai nama awal
            "username" => $username,
            "email" => $email,
            "phone" => $phone,
            "password" => Hash::make($password),
            "role" => "user",
        ]);

        return redirect()
            ->route("login")
            ->with(
                "auth_success",
                "Pendaftaran berhasil! Silakan login untuk melanjutkan.",
            );
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    /** Proses logout */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()
            ->route("login")
            ->with("auth_success", "Anda telah berhasil logout.");
    }
}
