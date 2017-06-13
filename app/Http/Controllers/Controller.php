<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\UploadedFile;
use Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function createAndSavePhoto(UploadedFile $photo, $path, $width = 512, $height = 512)
    {
        $filename = time() . '.' . $photo->getClientOriginalExtension();
        Image::make($photo)->resize($width, $height, function ($constraint) use($width, $height) {
                        if($width != $height){
                            $constraint->aspectRatio();
                        }
                    })->save(public_path($path . $filename));
        
        return $filename;
    }
}
