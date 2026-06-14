<?php

namespace App\Http\Controllers\Penjahit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Toko\EditRequest;
use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $toko = Toko::where('penjahit_id', $user->id)->first();
        return view('penjahit.toko.index', compact('toko'));
    }

    public function edit()
    {
        $user = auth()->user();
        $toko = Toko::where('penjahit_id', $user->id)->firstOrFail();
        return view('penjahit.toko.edit', compact('toko'));
    }

    public function update(EditRequest $request)
    {
        $user = auth()->user();
        $toko = Toko::where('penjahit_id', $user->id)->firstOrFail();

        try {
            $toko->update([
                'logo' => $request->hasFile('logo') ? $this->uploadImage($request->file('logo'), 'toko') : $toko->logo,
                'nama_toko' => $request->nama_toko,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat,
                'no_wa' => $request->no_wa,
                'bank' => $request->bank,
                'no_rekening' => $request->no_rekening,
                'atas_nama' => $request->atas_nama,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return redirect()->route('penjahit.toko.index')->with('success', 'Toko berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function geocode(Request $request)
    {
        $context = stream_context_create([
            'http' => ['header' => "User-Agent: GoJahit/1.0\r\n"]
        ]);

        // Reverse geocode: lat/lng → alamat
        if ($request->has('lat') && $request->has('lng')) {
            $url = 'https://nominatim.openstreetmap.org/reverse?lat=' . urlencode($request->lat) . '&lon=' . urlencode($request->lng) . '&format=json';

            $response = @file_get_contents($url, false, $context);
            if ($response === false) {
                return response()->json(['error' => 'Gagal menghubungi server peta.'], 500);
            }

            $data = json_decode($response, true);

            if (!empty($data['display_name'])) {
                return response()->json([
                    'latitude' => $data['lat'],
                    'longitude' => $data['lon'],
                    'alamat' => $data['display_name'],
                ]);
            }

            return response()->json(['error' => 'Lokasi tidak ditemukan.'], 404);
        }

        // Forward geocode: alamat → lat/lng
        $request->validate(['alamat' => 'required|string|max:500']);

        $url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($request->alamat) . '&format=json&limit=1&countrycodes=id';

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return response()->json(['error' => 'Gagal menghubungi server peta.'], 500);
        }

        $data = json_decode($response, true);

        if (!empty($data[0])) {
            return response()->json([
                'latitude' => $data[0]['lat'],
                'longitude' => $data[0]['lon'],
            ]);
        }

        return response()->json(['error' => 'Alamat tidak ditemukan.'], 404);
    }
}
