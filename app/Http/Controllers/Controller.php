<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

abstract class Controller
{
    //

    public function uploadImage($image, $path)
    {
        $filename = Str::slug($image->getClientOriginalName(), '_') . '-' . time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs($path, $filename);
        return $filename;
    }
}
