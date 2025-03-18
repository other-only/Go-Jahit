<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Produk\EditRequest;
use App\Http\Requests\Admin\Produk\StoreRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->hasRole('penjahit')) {
            $produks = Product::all();
        } else {
            $produks = Product::all()->where('toko_id', $user->toko->id);
        }
        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        return view('admin.produk.add');
    }

    public function edit(Request $request, Product $produk)
    {
        return view('admin.produk.edit', compact('produk'));
    }

    public function store(StoreRequest $request)
    {
        try {
            Product::create([
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'foto' => $this->uploadImage($request->file('foto'), 'produk'),
                'toko_id' => auth()->user()->toko->id,
            ]);
            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(EditRequest $request, Product $produk)
    {
        try {
            $user = auth()->user();
            if (!$user->hasRole('penjahit')) {
                $toko = $produk->toko_id;
            } else {
                $toko = $user->toko->id;
            }
            $produk->update([
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'foto' => $request->hasFile('foto') ? $this->uploadImage($request->file('foto'), 'produk') : $produk->foto,
                'toko_id' => $toko,
            ]);
            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
