<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(){
        // $collection = Collection::
        //      join('collection_product', 'collections.id', '=', 'collection_product.collection_id')
        //     ->join('collection_product', 'products.id', '=', 'collection_product.product_id')
        //     ->select('collections.*', 'collection_product.product_id','product.*')
        //     ->get();
        $collection = Collection::where('status',true)->with('product',function($q){
            $q->with('firstVariant')->limit(12);
        })->get();
        return response()->json($collection);
    }
}
