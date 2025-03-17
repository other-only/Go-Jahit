<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole('penjahit')) {
            $details = ProductDetail::all();
        } else {
            $details = ProductDetail::all()->where('toko_id', $user->toko->id);
        }
        return view('admin.detail.index', compact('details'));
    }
}
