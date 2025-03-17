<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole('penjahit')) {
            $tokos = Toko::all();
        } else {
            $tokos = Toko::all()->where('penjahit_id', $user->id);
        }
        return view('admin.toko.index', compact('tokos'));
    }
}
