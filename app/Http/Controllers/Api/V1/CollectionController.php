<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Public\CollectionResource;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(){
        
        $collection = Collection::where('status',true)->with('product',function($q){
            $q->with('favorites',function($q){
                $q->where('user_id',Auth::guard('sanctum')->id());
            })->where('status',true)->with('variant',function($q){
                $q->leftJoin('color_families','color_families.name','variants.color')->select('variants.*','color_families.code');
            })->limit(12);
        })->take(8)->get();
        return CollectionResource::collection($collection);
    }
}
