<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function update(Request $request,$id){
        $variant = Variant::findOrFail($id);
        $color = $request->has('color');
        $sizes = $request->has('sizes');
        if(isset($color)){
            $variant->color = $color;
        }
        if(isset($sizes)){
            $variant->sizes = $sizes;
        }
        $variant->sku;
        $variant->price;
        $variant->stock;
        $variant->update();
        return response()->json([
            'message'=>'Variant Updated successfully'
        ]);
    }

    public function save(Request $request){
        $variant = new Variant();
        $color = $request->has('color');
        $sizes = $request->has('sizes');
        if(isset($color)){
            $variant->color = $color;
        }
        if(isset($sizes)){
            $variant->sizes = $sizes;
        }
        $variant->sku;
        $variant->price;
        $variant->stock;
        $variant->update();
        return response()->json([
            'message'=>'Variant Updated successfully'
        ]);
    }

    public function delete($id){
        $variant = Variant::findOrFail($id);
        $variant->delete();
        return response()->json([
            'message'=>'Variant removed successfully'
        ]);
    }
}
