<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helper\Datatable;
use App\Http\Resources\Public\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request){
        $search_text = $request->has('query')?$request->get('query'):null;
        $product = Product::where('status',true);
        if(isset($search_text) && $search_text!=""){
            $product = $product->where('title','like','%'.$search_text.'%')->orWhere('search_text','like','%'.$search_text.'%');
        }else{
            return "search text not available";
        }
        
        $product = $product->with(['favorites'=>function($q){
            $q->where('user_id',Auth::guard('sanctum')->id());
        },'variant'=>function($q){
            $q->leftJoin('color_families','color_families.name','variants.color')
            ->select('variants.*','color_families.code');
        }])->simplePaginate(20);
        return ProductResource::collection($product);
    }
}
