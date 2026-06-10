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
                'logo' => $request->logo ? $this->uploadImage($request->file('logo'), 'toko') : $toko->logo,
                'nama_toko' => $request->nama_toko,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat,
                'no_wa' => $request->no_wa,
                'bank' => $request->bank,
                'no_rekening' => $request->no_rekening,
                'atas_nama' => $request->atas_nama,
            ]);
            return redirect()->route('penjahit.toko.index')->with('success', 'Toko berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
