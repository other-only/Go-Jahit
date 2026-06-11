<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DetailController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PenjahitController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\TokoController;
use App\Http\Controllers\Client\BelanjaController;
use App\Http\Controllers\Client\ChatController as ClientChatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Penjahit\DashboardController as PenjahitDashboardController;
use App\Http\Controllers\Penjahit\TokoController as PenjahitTokoController;
use App\Http\Controllers\Penjahit\ProdukController as PenjahitProdukController;
use App\Http\Controllers\Penjahit\DetailController as PenjahitDetailController;
use App\Http\Controllers\Penjahit\ChatController as PenjahitChatController;
use App\Http\Controllers\Penjahit\PesananController as PenjahitPesananController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('client.belanja');
});

Route::get('login', [LoginController::class, 'login'])->middleware('guest')->name('login');
Route::post('login', [LoginController::class, 'postLogin'])->middleware('guest')->name('login.post');
Route::get('register', [LoginController::class, 'register'])->middleware('guest')->name('register');
Route::post('register', [LoginController::class, 'postRegister'])->middleware('guest')->name('register.post');

Route::get('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::group(['prefix' => 'client'], function () {
    Route::get('belanja', [BelanjaController::class, 'index'])->name('client.belanja');
    Route::post('geocode', [BelanjaController::class, 'geocode'])->name('client.geocode');
    Route::get('order/{toko}', [BelanjaController::class, 'order'])->name('client.order');
    Route::post('order/{toko}', [BelanjaController::class, 'orderPost'])->name('client.order.post');
    Route::get('order/{order}/success', [BelanjaController::class, 'orderSuccess'])->name('client.order.success');
    Route::get('order/{order}/status', [BelanjaController::class, 'orderStatus'])->name('client.order.status');
    Route::get('track/order', [BelanjaController::class, 'trackOrder'])->name('client.track.order');
    Route::post('track/order', [BelanjaController::class, 'trackOrderPost'])->name('client.track.order.post');
    Route::get('orders', [BelanjaController::class, 'historyOrder'])->name('client.history.order');
    Route::post('cancel/order', [BelanjaController::class, 'cancelOrder'])->name('client.cancel.order');

    // Client chat
    Route::group(['middleware' => ['auth', 'role:pelanggan']], function () {
        Route::get('chat', [ClientChatController::class, 'index'])->name('client.chat.index');
        Route::get('chat/{conversation}', [ClientChatController::class, 'show'])->name('client.chat.show');
        Route::get('chat/{conversation}/messages', [ClientChatController::class, 'fetchMessages'])->name('client.chat.messages');
        Route::post('chat/{conversation}/send', [ClientChatController::class, 'send'])->name('client.chat.send');
        Route::get('chat/start/{penjahit}', [ClientChatController::class, 'startGeneral'])->name('client.chat.start');
        Route::get('order/{order}/chat', [ClientChatController::class, 'startOrder'])->name('client.chat.order');
    });
});

// Admin routes — only for admin role
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::group(['prefix' => 'seting'], function () {
        Route::group(['prefix' => 'toko'], function () {
            Route::get('', [TokoController::class, 'index'])->name('admin.toko.index');
            Route::get('edit/{toko}', [TokoController::class, 'edit'])->name('admin.toko.edit');
            Route::post('update/{toko}', [TokoController::class, 'update'])->name('admin.toko.update');
        });
        Route::group(['prefix' => 'produk'], function () {
            Route::get('', [ProdukController::class, 'index'])->name('admin.produk.index');
            Route::get('add', [ProdukController::class, 'create'])->name('admin.produk.create');
            Route::post('store', [ProdukController::class, 'store'])->name('admin.produk.store');
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
        Route::get('detail/{order}', [OrderController::class, 'detail'])->name('admin.order.detail');
        Route::post('update/{order}/status', [OrderController::class, 'status'])->name('admin.order.status');
        Route::post('update/{order}/confirm', [OrderController::class, 'confirm'])->name('admin.order.confirm');
    });
    Route::group(['prefix' => 'penjahit'], function () {
        Route::get('', [PenjahitController::class, 'index'])->name('admin.penjahit.index');
        Route::get('add', [PenjahitController::class, 'create'])->name('admin.penjahit.create');
        Route::post('store', [PenjahitController::class, 'store'])->name('admin.penjahit.store');
    });
});

// Penjahit routes — only for penjahit role
Route::group(['prefix' => 'penjahit', 'middleware' => ['auth', 'role:penjahit']], function () {
    Route::get('dashboard', [PenjahitDashboardController::class, 'index'])->name('penjahit.dashboard');

    Route::group(['prefix' => 'toko'], function () {
        Route::get('', [PenjahitTokoController::class, 'index'])->name('penjahit.toko.index');
        Route::get('edit', [PenjahitTokoController::class, 'edit'])->name('penjahit.toko.edit');
        Route::post('update', [PenjahitTokoController::class, 'update'])->name('penjahit.toko.update');
        Route::post('geocode', [PenjahitTokoController::class, 'geocode'])->name('penjahit.toko.geocode');
    });

    Route::group(['prefix' => 'produk', 'middleware' => 'has.toko'], function () {
        Route::get('', [PenjahitProdukController::class, 'index'])->name('penjahit.produk.index');
        Route::get('add', [PenjahitProdukController::class, 'create'])->name('penjahit.produk.create');
        Route::post('store', [PenjahitProdukController::class, 'store'])->name('penjahit.produk.store');
        Route::get('edit/{produk}', [PenjahitProdukController::class, 'edit'])->name('penjahit.produk.edit');
        Route::post('update/{produk}', [PenjahitProdukController::class, 'update'])->name('penjahit.produk.update');
        Route::delete('delete/{produk}', [PenjahitProdukController::class, 'destroy'])->name('penjahit.produk.delete');
    });

    Route::group(['prefix' => 'detail', 'middleware' => 'has.toko'], function () {
        Route::get('', [PenjahitDetailController::class, 'index'])->name('penjahit.detail.index');
        Route::get('add', [PenjahitDetailController::class, 'create'])->name('penjahit.detail.create');
        Route::post('store', [PenjahitDetailController::class, 'store'])->name('penjahit.detail.store');
        Route::get('edit/{detail}', [PenjahitDetailController::class, 'edit'])->name('penjahit.detail.edit');
        Route::post('update/{detail}', [PenjahitDetailController::class, 'update'])->name('penjahit.detail.update');
        Route::delete('delete/{detail}', [PenjahitDetailController::class, 'destroy'])->name('penjahit.detail.delete');
    });

    Route::group(['prefix' => 'pesanan', 'middleware' => 'has.toko'], function () {
        Route::get('', [PenjahitPesananController::class, 'index'])->name('penjahit.pesanan.index');
        Route::get('detail/{order}', [PenjahitPesananController::class, 'detail'])->name('penjahit.pesanan.detail');
        Route::post('update/{order}/status', [PenjahitPesananController::class, 'status'])->name('penjahit.pesanan.status');
        Route::post('update/{order}/confirm', [PenjahitPesananController::class, 'confirm'])->name('penjahit.pesanan.confirm');
    });

    // Penjahit chat
    Route::group(['middleware' => 'has.toko'], function () {
        Route::get('chat', [PenjahitChatController::class, 'index'])->name('penjahit.chat.index');
        Route::get('chat/{conversation}', [PenjahitChatController::class, 'show'])->name('penjahit.chat.show');
        Route::get('chat/{conversation}/messages', [PenjahitChatController::class, 'fetchMessages'])->name('penjahit.chat.messages');
        Route::post('chat/{conversation}/send', [PenjahitChatController::class, 'send'])->name('penjahit.chat.send');
    });
});
