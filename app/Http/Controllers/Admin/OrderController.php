<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function detail(Order $order)
    {
        return view('admin.order.detail', compact('order'));
    }

    public function status(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();
            $order->status = $request->status;
            $order->save();
            DB::commit();
            return back()->with('success', 'Status Berhasil Diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function confirm(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();
            $order->status = 'selesai';
            $order->save();
            DB::commit();
            return back()->with('success', 'Order Berhasil Dikonfirmasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
