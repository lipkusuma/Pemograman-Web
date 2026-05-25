<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Jika user sudah login, redirect ke halaman yang sesuai.
     * Cegah user yang sudah login mengakses halaman login/register.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has("username")) {
            if ($request->session()->get("role") === "admin") {
                return redirect()->route("dashboard");
            }
            return redirect()->route("katalog");
        }

        return $next($request);
    }
}
