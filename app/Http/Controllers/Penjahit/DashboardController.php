<?php

namespace App\Http\Controllers\Penjahit;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $produkCount = 0;
        $detailCount = 0;
        $orderCount = 0;
        $recentOrders = collect();

        if ($user->toko) {
            $produkCount = Product::where('toko_id', $user->toko->id)->count();
            $detailCount = ProductDetail::where('toko_id', $user->toko->id)->count();
            $orderCount = Order::where('toko_id', $user->toko->id)->count();
            $recentOrders = Order::where('toko_id', $user->toko->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('penjahit.dashboard', compact(
            'produkCount',
            'detailCount',
            'orderCount',
            'recentOrders'
        ));
    }
}
