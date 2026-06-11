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
use App\Http\Controllers\CartController;
use App\Http\Controllers\SupportChatController;

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
    Route::get("/profile", [ProfileController::class, "index"])->name("profile");
    Route::post("/profile/upload", [ProfileController::class, "upload"])->name("profile.upload");
    Route::post("/profile/personal-info", [ProfileController::class, "updatePersonalInfo"])->name("profile.updatePersonalInfo");
    Route::post("/profile/bio", [ProfileController::class, "updateBio"])->name("profile.updateBio");
    Route::post("/profile/password", [ProfileController::class, "updatePassword"])->name("profile.updatePassword");

    // Cart & Checkout Routes
    Route::get("/cart", [CartController::class, "index"])->name("cart.index");
    Route::post("/cart/add/{product}", [CartController::class, "add"])->name("cart.add");
    Route::post("/cart/update/{cart}", [CartController::class, "update"])->name("cart.update");
    Route::delete("/cart/delete/{cart}", [CartController::class, "delete"])->name("cart.delete");
    Route::get("/checkout", [CartController::class, "checkout"])->name("cart.checkout");
    Route::post("/checkout", [CartController::class, "processCheckout"])->name("cart.processCheckout");
    Route::get("/payment/{transaction}", [CartController::class, "payment"])->name("cart.payment");
    Route::post("/payment/{transaction}", [CartController::class, "processPayment"])->name("cart.processPayment");
    Route::get("/payment-success/{transaction}", [CartController::class, "paymentSuccess"])->name("cart.paymentSuccess");

// (moved) Interface testing endpoint declared below (outside auth middleware group)

    // Support chat
    Route::get('/support-chat', [SupportChatController::class, 'index'])->name('support.chat');
    Route::post('/support-chat/message', [SupportChatController::class, 'send'])->name('support.chat.send');
    Route::get('/support-chat/messages', [SupportChatController::class, 'messages'])->name('support.chat.messages');

    // Admin support routes
    Route::middleware('admin.check')->group(function () {
        Route::get('/admin/support', [\App\Http\Controllers\AdminSupportController::class, 'index'])->name('admin.support.index');
        Route::get('/admin/support/notifications', [\App\Http\Controllers\AdminSupportController::class, 'notifications'])->name('admin.support.notifications');
        Route::get('/admin/support/sse', [\App\Http\Controllers\AdminSupportController::class, 'sseNotifications'])->name('admin.support.sse');
        Route::get('/admin/support/chat/{id}', [\App\Http\Controllers\AdminSupportController::class, 'showChat'])->name('admin.support.chat');
        Route::post('/admin/support/chat/{id}/reply', [\App\Http\Controllers\AdminSupportController::class, 'sendReply'])->name('admin.support.reply');
    });

// Debug route (DEV only) - shows session and support/admin routes
if (env('APP_ENV') !== 'production') {
    Route::get('/debug/session', [\App\Http\Controllers\DebugController::class, 'sessionInfo']);
    Route::get('/debug/force-admin', [\App\Http\Controllers\DebugController::class, 'forceAdmin']);
    Route::get('/debug/view-admin', function () {
        $chats = \App\Models\Chat::with(['messages' => function ($q) { $q->latest()->limit(1); }])->get()->map(function ($c) {
            $last = $c->messages()->with('user')->orderBy('created_at', 'desc')->first();
            return [
                'id' => $c->id,
                'user_id' => $c->user_id,
                'last_message' => $last ? $last->message : null,
                'last_at' => $last ? $last->created_at->toDateTimeString() : null,
            ];
        });
        return view('support.admin_index', compact('chats'));
    });
}

    // Route khusus Admin
    Route::middleware("admin.check")->group(function () {
        Route::get("/dashboard", [DashboardController::class, "index"])->name(
            "dashboard",
        );
        Route::get("/stok-barang", [
            StokBarangController::class,
            "index",
        ])->name("stok_barang");
        Route::post("/stok-barang", [
            StokBarangController::class,
            "store",
        ])->name("stok_barang.store");
        Route::get("/stok-barang/{id}/edit", [
            StokBarangController::class,
            "edit",
        ])->name("stok_barang.edit");
        Route::put("/stok-barang/{id}", [
            StokBarangController::class,
            "update",
        ])->name("stok_barang.update");
        Route::delete("/stok-barang/{id}", [
            StokBarangController::class,
            "destroy",
        ])->name("stok_barang.destroy");
        Route::get("/laporan", [LaporanController::class, "index"])->name(
            "laporan",
        );
    });
});

// Interface testing endpoint (no auth required) - explicitly disable CSRF middleware for this route
Route::post('/interface/checkout', [CartController::class, 'processCheckoutApi'])
    ->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ]);
