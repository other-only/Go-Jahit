<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BelanjaController extends Controller
{
    public function index(Request $request)
    {
        $tokos = Toko::all();
        return view('client.list_toko', compact('tokos'));
    }

    public function order(Request $request, Toko $toko)
    {
        $produks = $toko->produks;
        $details = $toko->details;
        return view('client.order', compact('toko', 'produks', 'details'));
    }

    public function orderPost(Request $request, Toko $toko)
    {
        try {
            DB::beginTransaction();
            $kode_booking = 'BK-' . mt_rand(10000000, 99999999);
            $order = Order::create([
                'kode_order' => $kode_booking,
                'toko_id' => $toko->id,
                'product_id' => $request->productType,
                'product_detail_id' => $request->fabricType,
                'total_harga' => $request->total_price,
                'bayar' => $request->paymentMethod,
                'jumlah' => $request->quantity,
                'nama_penerima' => $request->name,
                'alamat_penerima' => $request->address,
                'no_hp_penerima' => $request->phone,
                'bukti_pembayaran' => $request->paymentMethod == 'transfer' ? $this->uploadImage($request->file('bukti_transfer'), 'bukti_pembayaran') : null,
            ]);
            DB::commit();
            $url = route('client.order.success', ['order' => $order]);
            return response()->json(['message' => 'Order Berhasil', 'url' => $url, 'status' => true], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function orderSuccess(Request $request, Order $order)
    {
        return view('client.boking_code', compact('order'));
    }

    public function orderStatus(Request $request, $order)
    {
        $order = Order::where('kode_order', $order)->first();
        return view('client.order_status', compact('order'));
    }
}
