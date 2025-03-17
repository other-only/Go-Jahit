<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProdukController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->hasRole('penjahit')) {
            $produks = Product::all();
        } else {
            $produks = Product::all()->where('toko_id', $user->toko->id);
        }
        return view('admin.produk.index', compact('produks'));
    }
}
