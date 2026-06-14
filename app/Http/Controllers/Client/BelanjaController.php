<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BelanjaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $lat = $request->get('lat');
        $lng = $request->get('lng');

        $query = Toko::has('produks')->has('details')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_toko', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            });

        $tokos = $query->get();

        if ($lat && $lng) {
            foreach ($tokos as $toko) {
                if ($toko->latitude && $toko->longitude) {
                    $toko->distance = $this->haversine(
                        $lat, $lng,
                        $toko->latitude, $toko->longitude
                    );
                } else {
                    $toko->distance = null;
                }
            }

            $tokos = $tokos->sortBy(function ($t) {
                return $t->distance ?? PHP_FLOAT_MAX;
            })->values();
        }

        // Manual pagination
        $perPage = 12;
        $page = $request->get('page', 1);
        $total = $tokos->count();
        $paginated = new LengthAwarePaginator(
            $tokos->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('client.list_toko', compact('paginated', 'search', 'lat', 'lng'));
    }

    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
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
        $request->validate([
            'productType' => 'required',
            'fabricType' => 'required',
            'clothing_quantity' => 'required|numeric|min:1',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:15',
            'paymentMethod' => 'required|in:cod,transfer',
            'total_price' => 'required|numeric',
        ]);

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

        if (!$order) {
            return redirect()->route('client.track.order')->with('error', 'Kode booking tidak ditemukan.');
        }

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

            if (!$order) {
                DB::rollBack();
                return back()->with('error', 'Order tidak ditemukan.');
            }

            if (in_array($order->status, ['selesai', 'batal'])) {
                DB::rollBack();
                return back()->with('error', 'Order dengan status ' . $order->getStatusOrder() . ' tidak dapat dibatalkan.');
            }

            $order->status = 'batal';
            $order->save();
            DB::commit();
            return back()->with('success', 'Order Berhasil Dibatalkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function geocode(Request $request)
    {
        $request->validate(['alamat' => 'required|string|max:500']);

        $url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($request->alamat) . '&format=json&limit=1&countrycodes=id';

        $context = stream_context_create([
            'http' => [
                'header' => "User-Agent: GoJahit/1.0\r\n"
            ]
        ]);

        $response = file_get_contents($url, false, $context);
        $data = json_decode($response, true);

        if (!empty($data[0])) {
            $lat = $data[0]['lat'];
            $lng = $data[0]['lon'];
            return redirect()->route('client.belanja', ['lat' => $lat, 'lng' => $lng]);
        }

        return redirect()->route('client.belanja')->with('error', 'Alamat tidak ditemukan. Silakan coba alamat lain.');
    }
}
