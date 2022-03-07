<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\Public\ProductResource;
use App\Http\Resources\Public\product\ProductDetailResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function categoryProduct($slug){
        $category = Category::where('slug',$slug)->select('id','name','slug')->with('options')->first();
        $product = Product::where('category_id',$category->id)->with('firstVariant','collection')->paginate(10);
        ;
        
        return response()->json([
            'category' => $category,
            'product'  => ProductResource::collection($product)
        ]);
    }

    public function productDetail($id){
        $product = Product::
        join('categories','categories.id','products.category_id')
        ->leftJoin('brands','brands.id','products.brand_id')
        ->select('products.*','categories.name as category_name','brands.name as brand_name')
        ->with(['images','optionValues','variant'])
        ->findOrFail($id);
        
        return new ProductDetailResource($product);
        
    }
}
