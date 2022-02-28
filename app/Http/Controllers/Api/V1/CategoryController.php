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
                $q->select('id','name','slug','parent_id','status')
                ->with('children:id,name,slug,status,parent_id');
            })->orderBy('name','asc')->get();
        });
        return  CategoryResource::collection($categories)->response()
        ->setStatusCode(200);
    }

}