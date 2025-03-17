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
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjahit_id');

            $table->string('nama_toko');
            $table->text('deskripsi');
            $table->text('alamat');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('logo');

            $table->timestamps();

            $table->foreign('penjahit_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokos');
    }
};
