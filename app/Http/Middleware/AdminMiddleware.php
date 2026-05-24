<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Cek apakah user memiliki role 'admin'.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('role') !== 'admin') {
            return redirect()->route('katalog');
        }

        return $next($request);
    }
}
