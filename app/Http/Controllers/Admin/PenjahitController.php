<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Penjahit\StoreRequest;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenjahitController extends Controller
{
    public function index(Request $request)
    {
        $penjahits = User::role('penjahit')->paginate(10);
        return view('admin.penjahit.index', compact('penjahits'));
    }
    public function create(Request $request)
    {
        return view('admin.penjahit.add');
    }

    public function store(StoreRequest $request)
    {
        // Begin transaction
        DB::beginTransaction();

        try {
            // Simpan data user (penjahit)
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);

            $user->syncRoles('penjahit');

            // Simpan data toko
            $toko = Toko::create([
                'penjahit_id' => $user->id,
                'nama_toko' => $request['nama_toko'],
                'alamat' => $request['alamat_toko'],
                'deskripsi' => $request['deskripsi_toko'],
                'logo' => $this->uploadImage($request->file('foto_toko'), 'toko'),
                'no_wa' => $request['no_wa'], // Default null, bisa ditambahkan nanti
                'bank' => $request['bank'], // Default null, bisa ditambahkan nanti
                'no_rekening' => $request['no_rekening'], // Default null, bisa ditambahkan nanti
                'atas_nama' => $request['atas_nama'], // Default null, bisa ditambahkan nanti
            ]);

            // Commit transaction
            DB::commit();

            return redirect()->route('admin.penjahit.index')
                ->with('success', 'Penjahit berhasil ditambahkan');

        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
