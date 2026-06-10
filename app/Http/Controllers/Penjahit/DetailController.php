<?php

namespace App\Http\Controllers\Penjahit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Detail\EditRequest;
use App\Http\Requests\Admin\Detail\StoreRequest;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $details = ProductDetail::where('toko_id', $user->toko->id)->paginate(10);
        return view('penjahit.detail.index', compact('details'));
    }

    public function create()
    {
        return view('penjahit.detail.add');
    }

    public function store(StoreRequest $request)
    {
        try {
            ProductDetail::create([
                'toko_id' => auth()->user()->toko->id,
                'nama_detail' => $request->nama_detail,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'foto' => $this->uploadImage($request->file('foto'), 'detail'),
            ]);
            return redirect()->route('penjahit.detail.index')->with('success', 'Detail berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(ProductDetail $detail)
    {
        $user = auth()->user();
        if ($detail->toko_id !== $user->toko->id) {
            abort(403);
        }
        return view('penjahit.detail.edit', compact('detail'));
    }

    public function update(EditRequest $request, ProductDetail $detail)
    {
        $user = auth()->user();
        if ($detail->toko_id !== $user->toko->id) {
            abort(403);
        }

        try {
            $detail->update([
                'nama_detail' => $request->nama_detail,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'foto' => $request->hasFile('foto') ? $this->uploadImage($request->file('foto'), 'detail') : $detail->foto,
            ]);
            return redirect()->route('penjahit.detail.index')->with('success', 'Detail berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(ProductDetail $detail)
    {
        $user = auth()->user();
        if ($detail->toko_id !== $user->toko->id) {
            abort(403);
        }

        try {
            $detail->delete();
            return redirect()->route('penjahit.detail.index')->with('success', 'Detail berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
