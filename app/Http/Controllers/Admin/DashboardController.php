<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tokoCount = Toko::count();
        $penjahitCount = User::role('penjahit')->count();
        $produkCount = Product::count();
        $orderCount = Order::count();

        $recentOrders = Order::latest()->take(5)->get();

        $orderStats = [
            'menunggu' => Order::where('status', 'menunggu-konfirmasi')->count(),
            'proses' => Order::where('status', 'dalam-proses')->count(),
            'dikirim' => Order::where('status', 'sudah-dikirim')->count(),
            'selesai' => Order::where('status', 'selesai')->count(),
        ];

        return view('admin.dashboard', compact(
            'tokoCount',
            'penjahitCount',
            'produkCount',
            'orderCount',
            'recentOrders',
            'orderStats'
        ));
    }
}
