<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function getFoto()
    {
        return Storage::url('produk/' . $this->foto);
    }
}
