<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Category;
use App\Models\ProductType;
use App\Models\Brand;
use App\Models\ColorFamily;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\CategoryOption;
use App\Models\Collection;
use App\Models\Option;
use Illuminate\Http\Request;

class CommonDataController extends Controller
{
    public function allCategories(){
        $categories = Category::select('id','name','parent_id')->where('parent_id',null)->get();
        return response()->json($categories,200);

    }

    public function getAllCategoryWithSubcategory(){
        $categories = Category::select('id','name','parent_id')->where('parent_id',null)->get();
        return CategoryResource::collection($categories)->response();
    }

    public function getProductTypes(){
        $product_types = ProductType::select('id','title','field')->get();
        return response()->json($product_types,200);
    }

    public function getCategoryBrand($id){
        $brands = Brand::where('category_id',$id)->select('id','name')->get();
        return response()->json($brands,200);
    }

    public function categoryOptions($id){
        $categoryOptions = CategoryOption::where('category_id',$id)->get();
        $option_ids = [];
        foreach($categoryOptions as $option){
            array_push($option_ids,$option->option_id);
        }

        $options= Option::whereIn('id',$option_ids)->get();
        return response()->json($options);
    }

    public function getColors(){
        $colors = ColorFamily::select('id','name','code')->get();
        return response()->json($colors);
    }

    public function getCollection(){
        $collection = Collection::select('id','name')->get();
        return response()->json($collection,200);
    }

}
