<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        // Menggunakan Facade Auth lebih disukai editor daripada helper auth()
        if (Auth::check() && Auth::user()->role === 'owner') {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini khusus Owner.');
    }
}