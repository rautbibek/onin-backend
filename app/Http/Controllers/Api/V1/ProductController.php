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
    public function categoryProduct(Request $request,$id){
        
        $min_price = $request->has('min')?request()->get('min'):0;
        $max_price = $request->has('max')?$request->get('max'):null;
        $option_filter = collect($request->except(['min','max','brand_id','brand']))->keys();        
        $product = Product::where('category_id',$id)->where('status',true);
        
        if(request()->has('brand_id')){
            $product = $product->where('brand_id',request()->get('brand_id'));
        }
        // filter by options
        if($option_filter->count()>0){
            $product = $product->with('optionValues')->whereHas('optionValues',function($query) use($option_filter){
                foreach($option_filter as $option){
                    $str_arr = explode (",", request()->get($option)); 
                    $query->where('option',request()->get($option))->OrWhereIn('option_value',$str_arr);
                }
            }); 
        }
        //favorite product and min max price filter and color options
        $product = $product->with(['favorites'=>function($q){

            $q->where('user_id',Auth::guard('sanctum')->id());
        },'variant'=>function($q) use($min_price,$max_price){

            $q = $q->where('price','>=',$min_price);
            if(isset($max_price)){
                $q= $q->where('price','<=',$max_price);
            }
           
            $q->leftJoin('color_families','color_families.name','variants.color')
              ->select('variants.*','color_families.code');
            
         },'collection']);
         if(isset($min_price) ||isset($max_price)){
             $product = $product->HasVariant($min_price,$max_price);
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
