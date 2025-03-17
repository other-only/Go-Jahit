<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole('penjahit')) {
            $orders = Order::all();
        } else {
            $orders = Order::all()->where('toko_id', $user->toko->id);
        }
        return view('admin.order.index', compact('orders'));
    }
}
