<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    protected $guarded = [];

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function produk()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function detail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }

    public function getBuktiBayar()
    {
        return Storage::url('bukti_bayar/' . $this->bukti_bayar);
    }
}
