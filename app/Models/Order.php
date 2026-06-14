<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory;
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

    public function chat()
    {
        return $this->hasOne(Conversation::class)->where('type', 'order');
    }

    public function getBuktiBayar()
    {
        return Storage::url('bukti_pembayaran/' . $this->bukti_pembayaran);
    }

    public function getStatusOrder()
    {
        switch ($this->status) {
            case 'dalam-proses':
                $status = "Dalam Proses";
                break;
            case 'sudah-dikirim':
                $status = "Sudah Dikirim";
                break;
            case 'selesai':
                $status = "Selesai";
                break;
            case 'batal':
                $status = "Dibatalkan";
                break;
            default:
                $status = "Menunggu Konfirmasi";
                break;
        }

        return $status;
    }

    public function getStatusColor()
    {
        switch ($this->status) {
            case 'dalam-proses':
                $color = "warning";
                break;
            case 'sudah-dikirim':
                $color = "info";
                break;
            case 'selesai':
                $color = "success";
                break;
            case 'batal':
                $color = "danger";
                break;
            default:
                $color = "primary";
                break;
        }

        return $color;
    }
}
