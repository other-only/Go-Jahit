<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_order')->unique();

            $table->unsignedBigInteger('toko_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_detail_id');
            $table->unsignedBigInteger('pelanggan_id');

            $table->string('total_harga');
            $table->enum('bayar', ['cod', 'transfer']);
            $table->enum('status', ['dalam-proses', 'sudah-dikirim', 'menunggu-konfirmasi', 'selesai', 'batal'])->default('menunggu-konfirmasi');
            $table->string('jumlah_baju');
            $table->string('jumlah_kain');
            $table->string('ukuran_baju');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('nama_penerima');
            $table->string('alamat_penerima');
            $table->string('no_hp_penerima');
            $table->string('catatan')->nullable();

            $table->timestamps();

            $table->foreign('toko_id')->references('id')->on('tokos')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_detail_id')->references('id')->on('product_details')->onDelete('cascade');
            $table->foreign('pelanggan_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
