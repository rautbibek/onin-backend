<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
      }
    
      public function favorite($id){
        
        $product = Product::where('id',$id)->first();
        if(!$product){
            return response()->json([
                'message'=> 'unknown product error'
            ],500);
        }
        $user = Auth::user();
        $isfavorite = $user->favorites()->where('product_id',$id)->count();
        if($isfavorite == 0){
          $user->favorites()->attach($id);
          $message= "Product added to favorite list succefylly";
        }else{
          $user->favorites()->detach($id);
          $message= "Product remove from favorite list succefylly";
        }
        return response()->json([
            'message'=>$message,
        ],200);
      }
}
