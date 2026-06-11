<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Toko extends Model
{
    protected $guarded = [];

    public function getLogo()
    {
        return Storage::url('toko/' . $this->logo);
    }

    public function produks()
    {
        return $this->hasMany(Product::class, 'toko_id');
    }

    public function penjahit()
    {
        return $this->belongsTo(User::class, 'penjahit_id');
    }

    public function details()
    {
        return $this->hasMany(ProductDetail::class, 'toko_id');
    }

}
