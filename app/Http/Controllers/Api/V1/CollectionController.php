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
            $q->where('status',true)->with(['favorites'=>function($fv){
                $fv->where('user_id',Auth::guard('sanctum')->id());
            },'variant'=>function($vr){
                $vr->leftJoin('color_families','color_families.name','variants.color')->select('variants.*','color_families.code');
            }]);
            
        })->take(8)->get();
        return CollectionResource::collection($collection);
    }
}
