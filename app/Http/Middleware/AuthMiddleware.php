<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Cek apakah user sudah login (ada session 'username').
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has("username")) {
            return redirect()
                ->route("login")
                ->with(
                    "auth_error",
                    "Silakan login terlebih dahulu untuk mengakses halaman ini.",
                );
        }

        return $next($request);
    }
}
