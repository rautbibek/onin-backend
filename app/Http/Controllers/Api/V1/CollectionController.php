<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Http\Resources\Public\CollectionResource;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(){
        
        $collection = Collection::where('status',true)->with('product',function($q){
            $q->where('status',true)->with('variant',function($q){
                $q->leftJoin('color_families','color_families.name','variants.color')->select('variants.*','color_families.code');
            })->limit(12);
        })->get();
        return CollectionResource::collection($collection);
    }
}
