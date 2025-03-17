<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductDetail extends Model
{
    protected $guarded = [];

    public function getFoto()
    {
        return Storage::url('detail/' . $this->foto);
    }
}
