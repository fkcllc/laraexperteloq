<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveImage($image, $path='public')
    {
        if ($image) {
           return null;
        }

        // $imageName = time() . '.' . $image->getClientOriginalExtension();
        // $image->move(public_path($path), $imageName);
        // return $path . '/' . $imageName;

        // $imageName = time() . '.' . $image->getClientOriginalExtension();
        $imageName = time() . '.png';

        // save image
        \Storage::disk($path)->put($imageName, base64_decode($image));

        //return the path
        // URL is base url like localhost:8000
        return URL::to('/').'/storage/'.$path.'/'. $imageName;
    }
}
