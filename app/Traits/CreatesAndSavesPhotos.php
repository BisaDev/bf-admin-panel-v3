<?php

namespace Brightfox\Traits;
use Illuminate\Http\UploadedFile;
use Image;

trait CreatesAndSavesPhotos
{
    public function createAndSavePhoto(UploadedFile $photo, $path, $width = 512, $height = 512)
    {
        $filename = str_replace('.', '_', microtime(true)) . '.' . $photo->getClientOriginalExtension();
        
        $image = Image::make($photo);
    
        $preserve_aspect_ratio = false;
        if($width != $height){
            
            $preserve_aspect_ratio = true;
            //Swap width and height if image is portrait
            if($image->height() > $image->width()){
                $tmp = $width;
                $width = $height;
                $height = $tmp;
            }
        }
        
        $image->resize($width, $height, function ($constraint) use($preserve_aspect_ratio) {
            
            if($preserve_aspect_ratio){
                $constraint->aspectRatio();
            }
        })->save(public_path($path . $filename));
        
        return $filename;
    }
}