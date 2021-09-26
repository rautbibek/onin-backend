<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use Illuminate\Http\Request;

class CommonDataController extends Controller
{
    public function allCategories(){
        $categories = Category::get();
        return CategoryResource::collection($categories)->response();
    }

}
