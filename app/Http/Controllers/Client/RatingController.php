<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Order $order)
    {
        abort_unless($order->pelanggan_id === $request->user()->id, 403);
        abort_unless($order->status === 'selesai', 403);

        if ($order->rating()->exists()) {
            return back()->withErrors(['rating' => 'Rating untuk pesanan ini sudah pernah dikirim.']);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'ulasan' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Pilih jumlah bintang untuk rating.',
            'rating.between' => 'Rating harus bernilai antara 1 sampai 5 bintang.',
        ]);

        $order->rating()->create([
            'toko_id' => $order->toko_id,
            'pelanggan_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih, rating Anda berhasil dikirim.');
    }
}
