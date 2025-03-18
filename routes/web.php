<?php

use App\Http\Controllers\Admin\DetailController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\TokoController;
use App\Http\Controllers\Client\BelanjaController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('client.belanja');
});

Route::get('login', [LoginController::class, 'login'])->middleware('guest')->name('login');
Route::post('login', [LoginController::class, 'postLogin'])->middleware('guest')->name('login.post');
Route::get('register', [LoginController::class, 'register'])->middleware('guest')->name('register');
Route::post('register', [LoginController::class, 'postRegister'])->middleware('guest')->name('register.post');

Route::get('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::group(['prefix' => 'client', 'middleware' => 'auth'], function () {
    Route::get('belanja', [BelanjaController::class, 'index'])->name('client.belanja');
    Route::get('order/{toko}', [BelanjaController::class, 'order'])->name('client.order');
    Route::post('order/{toko}', [BelanjaController::class, 'orderPost'])->name('client.order.post');
    Route::get('order/{order}/success', [BelanjaController::class, 'orderSuccess'])->name('client.order.success');
    Route::get('order/{order}/status', [BelanjaController::class, 'orderStatus'])->name('client.order.status');
    Route::get('track/order', [BelanjaController::class, 'trackOrder'])->name('client.track.order');
    Route::post('track/order', [BelanjaController::class, 'trackOrderPost'])->name('client.track.order.post');
    Route::get('orders', [BelanjaController::class, 'historyOrder'])->name('client.history.order');
    Route::post('cancel/order', [BelanjaController::class, 'cancelOrder'])->name('client.cancel.order');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin|penjahit']], function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::group(['prefix' => 'seting'], function () {
        Route::group(['prefix' => 'toko'], function () {
            Route::get('', [TokoController::class, 'index'])->name('admin.toko.index');
            Route::get('edit/{toko}', [TokoController::class, 'edit'])->name('admin.toko.edit');
            Route::post('update/{toko}', [TokoController::class, 'update'])->name('admin.toko.update');
        });
        Route::group(['prefix' => 'produk'], function () {
            Route::get('', [ProdukController::class, 'index'])->name('admin.produk.index');
            Route::get('add', [ProdukController::class, 'create'])->middleware('role:penjahit')->name('admin.produk.create');
            Route::post('store', [ProdukController::class, 'store'])->middleware('role:penjahit')->name('admin.produk.store');
            Route::get('edit/{produk}', [ProdukController::class, 'edit'])->name('admin.produk.edit');
            Route::post('update/{produk}', [ProdukController::class, 'update'])->name('admin.produk.update');
        });
        Route::group(['prefix' => 'detail'], function () {
            Route::get('', [DetailController::class, 'index'])->name('admin.detail.index');
            Route::get('add', [DetailController::class, 'create'])->name('admin.detail.create');
            Route::post('store', [DetailController::class, 'store'])->name('admin.detail.store');
            Route::get('edit/{detail}', [DetailController::class, 'edit'])->name('admin.detail.edit');
            Route::post('update/{detail}', [DetailController::class, 'update'])->name('admin.detail.update');
        });
    });
    Route::group(['prefix' => 'order'], function () {
        Route::get('', [OrderController::class, 'index'])->name('admin.order.index');
    });
});
