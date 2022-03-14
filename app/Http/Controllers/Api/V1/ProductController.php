<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Public\ProductResource;
use App\Http\Resources\Public\product\ProductDetailResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function categoryProduct($id){
        //$category = Category::select('id','name','slug')->with('options')->findOrFail($id);
        
        $product = Product::where('category_id',$id)
                 ->with(['favorites'=>function($q){
                    $q->where('user_id',Auth::guard('sanctum')->id());
                },'variant'=>function($q){
                    $q->leftJoin('color_families','color_families.name','variants.color')->select('variants.*','color_families.code');
                 },'collection'])->paginate(20);
                 
        return ProductResource::collection($product);
       
        // return response()->json([
            
        //     'product'  => 

        // ],200);
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
        }])
        ->findOrFail($id);
        

        
        
        return new ProductDetailResource($product);
        
    }

    public function allProduct(){
        
        $product = Product::with(['favorites'=>function($q){
            $q->where('user_id',Auth::guard('sanctum')->id());
        },'variant'=>function($q){
            $q->leftJoin('color_families','color_families.name','variants.color')
            ->select('variants.*','color_families.code');
        }])->simplePaginate(20);
        
        return ProductResource::collection($product);
        // 'favorites'=>function($q){
        //     $query->select('user_id')->where('user_id',Auth::id());
        //  }
    }

    public function favoriteProduct(){
        $product  = product::where('id',21)->with('favorites',function($q){
            $q->where('user_id',1001)->count();
        })->get();
        
        return response()->json($product);
    }
}
