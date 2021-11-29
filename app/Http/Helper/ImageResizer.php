<?php
namespace App\Http\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Product_image;
class ImageResizer{
    public function resizeImage($img ,$product_id){

         //$images = $request->hasFile('image');
         foreach ($img as $image) {
           $currentDate = Carbon::now()->toDateString();
           $imagename = 'bikri-bazzar'.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
           if(!Storage::disk('public')->exists('product')){
             Storage::disk('public')->makeDirectory('product');
           }

           //resize the image
           $productImage = Image::make($image)->resize(1600,790,function($constrain){
             $constrain->aspectRatio();
           })
           ->insert(public_path('image/watermark.png'), 'center')

           ->stream();
           Storage::disk('public')->put('product/'.$imagename,$productImage);

           //thumbnail the image
           if(!Storage::disk('public')->exists('thumb')){
             Storage::disk('public')->makeDirectory('thumb');
           }
           $thumb = Image::make($image)->resize(200,150,function($constrain){
             $constrain->aspectRatio();
           })
           ->resizeCanvas(200,null,'center', false, '#e0e0e0')
           ->stream();
           Storage::disk('public')->put('thumb/'.$imagename,$thumb);

        //    $product_image = new Product_image();
        //    $product_image->product_id = $product_id;
        //    $product_image->image = $imagename;
        //    $product_image->save();
         }

    }
}
