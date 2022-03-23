<?php

namespace App\Http\Controllers\Api\V1\Customer;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Public\CartResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $cart = Cart::
        leftJoin('products','products.id','carts.product_id')
        ->leftJoin('variants','variants.id','carts.variant_id')
        
        ->select(
            'carts.id',
            'carts.user_id',
            'carts.product_id',
            'carts.quantity',
            'products.title',
            'products.discount_type',
            'products.discount',
            'products.cover',
            'variants.id as varaint_id',
            'variants.color',
            'variants.quantity as stock',
            'variants.price',
            
            'carts.updated_at'
        )->where('carts.user_id',auth()->id())
        ->get();
        //return $cart;
        return CartResource::collection($cart);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request,[
            
            'variant_id' => 'required',
            'quantity' => 'required|numeric|gt:0'
        ]);

        $id = $request->get('id');
        $size = $request->has('size')?$request->get('size'):null;
        $extra = null;
        if(isset($size)){
            $extra = [
                'size' => $size
            ];
        }

        $variant = DB::table('variants')->select('id','product_id','quantity')->where('id',$request->variant_id)->first();
        if(!$variant){
          return response()->json([
              'message'=> 'Product variant not found'
          ],422);  
        }
        //$product_id = $variant->product_id;
        
        try{
            if(isset($id)){
                $cart = Cart::findOrFail($id);
                $cart->update([
                    'variant_id' => $variant->id,
                    'quantity'   => $request->get('quantity'),
                    'extra'       => $extra
                ]);
                return response()->json([
                    'message' => 'Cart updated successfully.'
                ],200);
            }else{
                
                $cart = Cart::where('user_id',auth()->id())->where('variant_id',$request->variant_id)->first();
                
                if($cart){
                    if($request->quantity>1){
                        $qty = $request->quantity;
                    }else{
                        $qty = $cart->quantity + $request->quantity;
                    }
                    
                    if($qty > $variant->quantity){
                        return response()->json([
                            'message'=> 'Exceded product stock, only '.$variant->quantity.' stocks available',
                        ],422);
                    }
                    $cart->update([
                        'quantity' => $qty,
                        'variant_id' => $request->get('variant_id'),
                        'extra'       => $extra
                    ]);
                    return response()->json([
                        'message' => 'Cart updated successfully.'
                    ],200);
                }
                if($request->quantity > $variant->quantity ){
                    return response()->json([
                        'message'=> 'Exceded product stock, only '.$variant->quantity.' stocks available',
                    ],422);
                }
                Cart::create([
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'quantity'   => $request->get('quantity'),
                    'user_id'    => auth()->id(),
                    'extra'       => $extra
                ],200);
                return response()->json([
                    'message' => 'Item added to cart successfully.'
                ],200);
            }
        }catch(\Exception $e){
            return response()->json([
                'message'=> 'Something went wrong',
                'error' => $e
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'cart_id'=>'required',
            'quantity' => 'required|gt:0'
        ]);
        $cart = Cart::findOrFail($id);
        $variant = DB::table('variants')->select('id','product_id','quantity')
                   ->where('id',$request->variant_id)
                   ->first();
        if($cart->user_id == auth()->id()){
            //return $variant;
            if($request->quantity > $variant->quantity){
                return response()->json([
                    'message'=> 'Exceded product stocks, only '.$variant->quantity.' stocks available',
                ],422);
            }
            $cart->quantity = $request->quantity;
            $cart->update();
            return response()->json([
                'message'=> 'Cart updated successfully.'
            ],200);
        }
        return response()->json([
            'message'=>'Cart doesn`t belons to your.'
        ],422);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $cart = Cart::findOrFail($id);
            
            if($cart->user_id == auth()->id()){
                $cart->delete();
                return response()->json([
                    'message' => 'Cart item deleted successfully.'
                ],200);
            }else{
                return response()->json([
                    'message' => 'Unauthorized cart item.'
                ],419);
            }

        }catch(\Exception $e){
            return response()->json([
                'message'=> 'Something went wrong'
            ],500);
        }
        
    }
}
