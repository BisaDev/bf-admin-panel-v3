<?php

namespace Brightfox\Traits;
use Illuminate\Http\UploadedFile;
use Image;

trait CreatesAndSavesPhotos
{
    public function createAndSavePhoto(UploadedFile $photo, $path, $width = 512, $height = 512)
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