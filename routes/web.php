<?php

use App\Http\Controllers\Admin\Produk\ProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::group(['prefix' => 'produk'], function () {
        Route::get('', [ProdukController::class, 'index'])->name('admin.produk.index');
    });
});
