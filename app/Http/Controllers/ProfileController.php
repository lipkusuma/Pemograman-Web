<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('foto')) {
            return back()->with('pesan_error', 'Tidak ada file yang dipilih.');
        }

        $file = $request->file('foto');

        // Cek apakah ada error saat upload
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return back()->with('pesan_error', 'Terjadi kesalahan yang tidak diketahui!');
        }

        $ekstensiDiizinkan = ['jpg', 'png'];
        $ekstensi = strtolower($file->getClientOriginalExtension());

        if (!in_array($ekstensi, $ekstensiDiizinkan)) {
            return back()->with('pesan_error', "Gagal upload: Hanya file <strong>.jpg</strong> atau <strong>.png</strong> yang diperbolehkan! Anda mengupload file (.$ekstensi)");
        }

        // Generate nama file unik
        $namaAsli    = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $namaBersih  = str_replace(' ', '_', $namaAsli);
        $namaFileBaru = 'img_' . time() . '_' . $namaBersih . '.' . $ekstensi;

        // Simpan file ke folder public/uploads
        $file->move(public_path('uploads'), $namaFileBaru);

        // Simpan ke session
        session(['profile_pic' => $namaFileBaru]);

        return back()->with('pesan_sukses', "Berhasil! Foto tersimpan dengan nama: <strong>$namaFileBaru</strong>");
    }
}
