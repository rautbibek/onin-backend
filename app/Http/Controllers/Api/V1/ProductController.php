<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Public\ProductResource;
use App\Http\Resources\Public\product\ProductDetailResource;
use App\Http\Resources\Public\product\FavoriteProductResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function categoryProduct($id){
        $price = request()->has('price')?request()->get('price'):null;
        $product = Product::where('category_id',$id)->where('status',true);
        
        if(request()->has('brand_id')){
            $product = $product->where('brand_id',request()->get('brand_id'));
        }

        if(request()->has('price')){

        }
        $product = $product->with(['favorites'=>function($q){

            $q->where('user_id',Auth::guard('sanctum')->id());
        },'variant'=>function($q) use($price){

            // if(isset($price)){
            //     $q->PriceFilter($price[0],$price[1]);
            // }
            $q->leftJoin('color_families','color_families.name','variants.color')
              ->select('variants.*','color_families.code');
            
         },'collection']);
         if(isset($price)){
             $product = $product->HasVariant($price[0],$price[1]);
         }
        $product= $product->paginate(20);
        
        return ProductResource::collection($product);
    }

    

    public function productDetail($id){
        $product = Product::
        join('categories','categories.id','products.category_id')
        ->leftJoin('brands','brands.id','products.brand_id')
        ->select('products.*','categories.name as category_name','brands.name as brand_name')
        ->with(['favorites'=>function($q){
            $q->where('user_id',Auth::guard('sanctum')->id());
        },'images','optionValues','variant'=>function($q){
            $q->leftJoin('color_families','color_families.name','variants.color')->select('variants.*','color_families.code');
        }])->where('products.status',true)
        ->findOrFail($id);
        return new ProductDetailResource($product);
    }

    public function allProduct(){
        
        $product = Product::where('status',true)->with(['favorites'=>function($q){
            $q->where('user_id',Auth::guard('sanctum')->id());
        },'variant'=>function($q){
            $q->leftJoin('color_families','color_families.name','variants.color')
            ->select('variants.*','color_families.code');
        }])->simplePaginate(10);
        
        return ProductResource::collection($product);
        // 'favorites'=>function($q){
        //     $query->select('user_id')->where('user_id',Auth::id());
        //  }
    }

    public function favoriteProduct(){
        //  $product = Auth::guard('sanctum')->user()->favorites;
        $product  = User::where('id',Auth::guard('sanctum')->id())->with('favorites',function($q){
            $q->with(['variant']);
        })->first();
        
        return FavoriteProductResource::collection($product->favorites);
    }
}
