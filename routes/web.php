<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;

// Landing Page (publik)
Route::get("/", [HomeController::class, "index"])->name("home");

// Auth Routes (hanya untuk guest / belum login)
Route::middleware("guest.check")->group(function () {
    Route::get("/login", [AuthController::class, "loginForm"])->name("login");
    Route::post("/login", [AuthController::class, "login"])->name("auth.login");
    Route::get("/register", [AuthController::class, "registerForm"])->name(
        "register",
    );
    Route::post("/register", [AuthController::class, "register"])->name(
        "auth.register",
    );
});

// Logout (butuh login)
Route::post("/logout", [AuthController::class, "logout"])
    ->name("logout")
    ->middleware("auth.check");

// Route yang memerlukan login
Route::middleware("auth.check")->group(function () {
    Route::get("/katalog", [KatalogController::class, "index"])->name(
        "katalog",
    );
    Route::get("/transaksi", [TransaksiController::class, "index"])->name(
        "transaksi",
    );
    Route::get("/profile", [ProfileController::class, "index"])->name(
        "profile",
    );
    Route::post("/profile", [ProfileController::class, "upload"])->name(
        "profile.upload",
    );

    // Route khusus Admin
    Route::middleware("admin.check")->group(function () {
        Route::get("/dashboard", [DashboardController::class, "index"])->name(
            "dashboard",
        );
        Route::get("/stok-barang", [
            StokBarangController::class,
            "index",
        ])->name("stok_barang");
        Route::get("/laporan", [LaporanController::class, "index"])->name(
            "laporan",
        );
    });
});
