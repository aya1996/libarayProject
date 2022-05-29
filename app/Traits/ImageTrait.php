<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait ImageTrait
{
    public function saveImage($image, $path = null)
    {
        // $image = ;
        // $image = $image->file('image');
        // dd($image);
        // die;
        $name =  mt_rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path() . '/images/', $name);
        return $name;
    }

    public function updateImage($image, $path = null)
    {
       
        $name =  mt_rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path() . '/images/', $name);
        return $name;
    }
}
