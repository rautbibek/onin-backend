<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function update(Request $request){

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
        $id = $request->get('id');

        if(isset($id)){
            $variant = Variant::findOrFail($id);
            $color = $request->get('color');
            $sizes = $request->get('sizes');
            if(isset($color)){
                $variant->color = $color;
            }
            if(isset($sizes)){
                $variant->sizes = $sizes;
            }
            $variant->sku = $request->get('sku');
            $variant->price = $request->get('price');
            $variant->quantity = $request->get('quantity');
            $variant->update();
            return response()->json([
                'message'=>'Variant Updated successfully'
            ]);
        }else{
            $variant = new Variant();
            $color = $request->get('color');
            $sizes = $request->get('sizes');
            if(isset($color)){
                $variant->color = $color;
            }
            if(isset($sizes)){
                $variant->sizes = $sizes;
            }
            $variant->product_id = $request->get('product_id');
            $variant->sku = $request->get('sku');
            $variant->price = $request->get('price');
            $variant->quantity = $request->get('quantity');
            $variant->save();
            return response()->json([
                'message'=>'Variant added successfully'
            ]);
        }

    }

    public function delete($id){
        $variant = Variant::findOrFail($id);
        $variant->delete();
        return response()->json([
            'message'=>'Variant removed successfully'
        ]);
    }
}
