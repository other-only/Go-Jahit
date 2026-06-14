<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Toko\EditRequest;
use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index(Request $request)
    {
        $tokos = Toko::paginate(10);
        return view('admin.toko.index', compact('tokos'));
    }

    public function edit(Request $request, Toko $toko)
    {
        return view('admin.toko.edit', compact('toko'));
    }

    public function update(EditRequest $request, Toko $toko)
    {
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
            ]);
            return redirect()->route('admin.toko.index')->with('success', 'Toko berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
