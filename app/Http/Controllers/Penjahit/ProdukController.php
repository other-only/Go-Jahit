<?php

namespace App\Http\Controllers\Penjahit;

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
        $produks = Product::where('toko_id', $user->toko->id)->paginate(10);
        return view('penjahit.produk.index', compact('produks'));
    }

    public function create()
    {
        return view('penjahit.produk.add');
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
            return redirect()->route('penjahit.produk.index')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Product $produk)
    {
        $user = auth()->user();
        if ($produk->toko_id !== $user->toko->id) {
            abort(403);
        }
        return view('penjahit.produk.edit', compact('produk'));
    }

    public function update(EditRequest $request, Product $produk)
    {
        $user = auth()->user();
        if ($produk->toko_id !== $user->toko->id) {
            abort(403);
        }

        try {
            $produk->update([
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'foto' => $request->hasFile('foto') ? $this->uploadImage($request->file('foto'), 'produk') : $produk->foto,
                'toko_id' => $user->toko->id,
            ]);
            return redirect()->route('penjahit.produk.index')->with('success', 'Produk berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Product $produk)
    {
        $user = auth()->user();
        if ($produk->toko_id !== $user->toko->id) {
            abort(403);
        }

        try {
            $produk->delete();
            return redirect()->route('penjahit.produk.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
