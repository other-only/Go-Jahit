<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePenjahitHasToko
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->toko) {
            return redirect()->route('penjahit.toko.index')
                ->with('error', 'Anda belum memiliki toko. Silakan hubungi admin.');
        }

        return $next($request);
    }
}
