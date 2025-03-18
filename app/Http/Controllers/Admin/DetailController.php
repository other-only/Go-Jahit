<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Detail\EditRequest;
use App\Http\Requests\Admin\Detail\StoreRequest;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole('penjahit')) {
            $details = ProductDetail::all();
        } else {
            $details = ProductDetail::all()->where('toko_id', $user->toko->id);
        }
        return view('admin.detail.index', compact('details'));
    }

    public function create(Request $request)
    {
        return view('admin.detail.add');
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
            return redirect()->route('admin.detail.index')->with('success', 'Detail berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Request $request, ProductDetail $detail)
    {
        return view('admin.detail.edit', compact('detail'));
    }

    public function update(EditRequest $request, ProductDetail $detail)
    {
        try {
            $detail->update([
                'nama_detail' => $request->nama_detail,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'foto' => $request->hasFile('foto') ? $this->uploadImage($request->file('foto'), 'detail') : $detail->foto,
            ]);
            return redirect()->route('admin.detail.index')->with('success', 'Detail berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
