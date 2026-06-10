<?php

namespace App\Http\Controllers\Penjahit;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = Order::where('toko_id', $user->toko->id)->paginate(10);
        return view('penjahit.pesanan.index', compact('orders'));
    }

    public function detail(Order $order)
    {
        $user = auth()->user();
        if ($order->toko_id !== $user->toko->id) {
            abort(403);
        }
        return view('penjahit.pesanan.detail', compact('order'));
    }

    public function status(Request $request, Order $order)
    {
        $user = auth()->user();
        if ($order->toko_id !== $user->toko->id) {
            abort(403);
        }

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
        $user = auth()->user();
        if ($order->toko_id !== $user->toko->id) {
            abort(403);
        }

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
