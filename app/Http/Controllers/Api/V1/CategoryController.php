<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Public\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        
        $categories = cache()->remember('public-category',60*60*24,function(){
            return Category::where('status',true)->whereNull('parent_id')->with('children',function($q){
                $q->select('id','name','slug','parent_id','status','cover')
                ->with('children:id,name,slug,status,parent_id,cover');
            })->orderBy('name','asc')->get();
        });
        return  CategoryResource::collection($categories)->response()
        ->setStatusCode(200);
    }

    public function filterOptions($id){
        $category = Category::where('id',$id)
                  ->select('id','name','parent_id','slug')
                  ->with('brand:id,name,category_id')
                  ->FilterOptions()
                  ->first();
        return response()->json($category);
    }

}
