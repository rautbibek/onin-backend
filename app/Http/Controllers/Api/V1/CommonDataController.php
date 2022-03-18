<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\ProductType;
use App\Models\Brand;
use App\Models\State;
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

    public function states(){
        $state = DB::table('states')->select('id','name')->get();
        return response()->json($state);
    }

    public function districts(){
        $district = DB::table('districts')->select('id','name')->get();
        return response()->json($district);
    }

    public function cities(){
        $cities = DB::table('cities')->select('id','name')->get();
        return response()->json($cities);
    }

    public function getDistrictByState($state_id){
        $district = DB::table('districts')
                       ->where('state_id',$state_id)
                       ->select('id','name')
                       ->get();
        return response()->json($district);
    }

    public function getCityByDistrict($district_id){
        $city = DB::table('cities')->where('district_id',$district_id)
                   ->select('id','name','price')
                   ->get();
        return response()->json($city);
    }

    public function getLocalareaByCity($city_id){
        $localarea = DB::table('local_areas')->where('city_id',$city_id)
                   ->select('id','name','price')
                   ->get();
        return response()->json($localarea);
    }

}
