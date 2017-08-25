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
        
        if($width != $height){
            
            //Swap width and height if image is portrait
            if($image->height() > $image->width()){
                $tmp = $width;
                $width = $height;
                $height = $tmp;
            }
        }
        
        if(!(is_null($width) && is_null($height))){
    
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            
            if($width == $height){
                $image->fit($width);
            }
        }
        
        $image->save(public_path($path . $filename));
        
        return $filename;
    }
}