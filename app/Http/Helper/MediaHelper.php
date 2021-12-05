<?php
namespace App\Http\Helper;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaHelper{
    public function storeMedia($file,$path,$thumbnail=false, $icon=false,$image_type=true){
        return config('minio');
        if ($file) {
            $currentDate = Carbon::now()->toDateString();
            $fileName = $currentDate.'-'.uniqid().'.'.$file->getClientOriginalExtension();
            if($image_type){
                if($thumbnail){


                    $thumb = Image::make($file)->resize(200,150,function($constrain){
                        $constrain->aspectRatio();
                      })
                      ->resizeCanvas(200,null,'center', false, '#e0e0e0')
                      ->stream();
                      Storage::disk('public')->put('thumb/'.$fileName,$thumb);
                }
                //return "all";
                 Storage::disk('public')->put($path.'/'.$fileName, $file);
                 return $fileName;
            }else{
                Storage::disk('public')->put($path.'/'.$fileName, $file);
                 return $fileName;
            }

        }
    }

    public function getMedia(){
        //


    }
}
