<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DebugController extends Controller
{
    public function sessionInfo(Request $request)
    {
        $routes = collect(Route::getRoutes())->map(function ($r) {
            return [
                'uri' => $r->uri(),
                'name' => $r->getName(),
                'methods' => $r->methods(),
            ];
        })->filter(function ($r) {
            return str_contains($r['uri'], 'support') || str_contains($r['uri'], 'admin');
        })->values();

        return response()->json([
            'session' => [
                'role' => session('role'),
                'username' => session('username'),
            ],
            'routes' => $routes,
        ]);
    }

    public function forceAdmin(Request $request)
    {
        // Set session keys to simulate admin login for debugging (DEV only)
        $request->session()->put('role', 'admin');
        $request->session()->put('username', 'debug-admin');

        return redirect()->route('admin.support.index');
    }
}
