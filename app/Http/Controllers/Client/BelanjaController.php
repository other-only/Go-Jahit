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
        $search = $request->get('search');
        $tokos = Toko::has('produks')->has('details')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_toko', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->paginate(12);
        return view('client.list_toko', compact('tokos', 'search'));
    }

    public function order(Request $request, Toko $toko)
    {
        $produks = $toko->produks;
        $details = $toko->details;
        $user = auth()->user();
        return view('client.order', compact('toko', 'produks', 'details', 'user'));
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
                'jumlah_baju' => $request->clothing_quantity,
                'jumlah_kain' => $request->fabric_quantity,
                'ukuran_baju' => $request->size,
                'nama_penerima' => $request->name,
                'alamat_penerima' => $request->address,
                'no_hp_penerima' => $request->phone,
                'pelanggan_id' => auth()->user()->id,
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

    public function trackOrder(Request $request)
    {
        return view('client.cari_kode_boking');
    }

    public function trackOrderPost(Request $request)
    {
        $order = Order::where('kode_order', $request->kode_order)->first();
        return view('client.order_status', compact('order'));
    }

    public function historyOrder(Request $request)
    {
        $orders = Order::where('pelanggan_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('client.order_history', compact('orders'));
    }

    public function cancelOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = Order::where('kode_order', $request->kode_order)->first();
            $order->status = 'batal';
            $order->save();
            DB::commit();
            return back()->with('success', 'Order Berhasil Dibatalkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
