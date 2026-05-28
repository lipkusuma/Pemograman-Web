<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::find(session('user_id'));
        return view('profile.index', compact('user'));
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('foto')) {
            return back()->with('pesan_error', 'Tidak ada file yang dipilih.');
        }

        $file = $request->file('foto');

        if ($file->getError() !== UPLOAD_ERR_OK) {
            return back()->with('pesan_error', 'Terjadi kesalahan yang tidak diketahui!');
        }

        $ekstensiDiizinkan = ['jpg', 'png'];
        $ekstensi = strtolower($file->getClientOriginalExtension());

        if (!in_array($ekstensi, $ekstensiDiizinkan)) {
            return back()->with('pesan_error', "Gagal upload: Hanya file <strong>.jpg</strong> atau <strong>.png</strong> yang diperbolehkan!");
        }

        $namaAsli    = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $namaBersih  = str_replace(' ', '_', $namaAsli);
        $namaFileBaru = 'img_' . time() . '_' . $namaBersih . '.' . $ekstensi;

        $file->move(public_path('uploads'), $namaFileBaru);

        // Update database
        $user = User::find(session('user_id'));
        if ($user) {
            $user->profile_pic = $namaFileBaru;
            $user->save();
        }

        session(['profile_pic' => $namaFileBaru]);

        return back()->with('pesan_sukses', 'Foto profil berhasil diperbarui!');
    }

    public function updatePersonalInfo(Request $request)
    {
        $user = User::find(session('user_id'));
        if (!$user) {
            return back()->with('pesan_error', 'User tidak ditemukan.');
        }

        $name  = trim($request->input('name', ''));
        $email = trim($request->input('email', ''));
        $phone = trim($request->input('phone', ''));

        if (empty($name) || empty($email) || empty($phone)) {
            return back()->with('pesan_error', 'Semua field wajib diisi!');
        }

        // Cek email unik (kecuali milik sendiri)
        if (User::where('email', $email)->where('id', '!=', $user->id)->exists()) {
            return back()->with('pesan_error', 'Email sudah digunakan akun lain!');
        }

        $user->name  = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->save();

        session(['name' => $name, 'email' => $email]);

        return back()->with('pesan_sukses', 'Informasi pribadi berhasil diperbarui!');
    }

    public function updateBio(Request $request)
    {
        $user = User::find(session('user_id'));
        if (!$user) {
            return back()->with('pesan_error', 'User tidak ditemukan.');
        }

        $bio = trim($request->input('bio', ''));
        $user->bio = $bio;
        $user->save();

        return back()->with('pesan_sukses', 'Bio berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = User::find(session('user_id'));
        if (!$user) {
            return back()->with('pesan_error', 'User tidak ditemukan.');
        }

        $currentPassword = $request->input('current_password', '');
        $newPassword     = $request->input('new_password', '');
        $confirmPassword = $request->input('confirm_password', '');

        if (!Hash::check($currentPassword, $user->password)) {
            return back()->with('pesan_error', 'Password saat ini tidak cocok!');
        }

        if (strlen($newPassword) < 6) {
            return back()->with('pesan_error', 'Password baru minimal 6 karakter!');
        }

        if ($newPassword !== $confirmPassword) {
            return back()->with('pesan_error', 'Konfirmasi password tidak cocok!');
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return back()->with('pesan_sukses', 'Password berhasil diperbarui!');
    }
}
