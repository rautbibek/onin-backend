<?php

namespace App\Http\Controllers\Api\V1\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'delivery_address'=> 'required',
            'payment_type' => 'required',
            'delivery_charge' => 'required',

        ]);
        try{
            DB::beginTransaction();
            $cart = DB::table('carts')
                   ->join('products','products.id','=','carts.product_id')
                   ->join('variants','variants.id','=','carts.variant_id')
                   ->select(
                       'carts.*',
                       'products.title',
                       'products.discount_type',
                       'products.discount',
                       'products.status',
                       'variants.quantity as stock',
                       'variants.price'
                       )
                   ->where('carts.user_id',auth()->id())
                   ->get();
        //return $cart;
        if($cart->count()>0){
            $rand = mt_rand(1000, 9999);
            
            $order_identifier = mt_rand($rand, 9999).'-'.mt_rand($rand, 9999).'-'.mt_rand($rand, 9999).'-'.mt_rand($rand, $rand);
            $order = new Order();
            $order->delivery_address = $request->delivery_address;
            $order->order_identifier = $order_identifier;
            $order->user_id = auth()->id();
            $order->visibility = true;
            $order->status = 1;
            $order->delivery_charge = $request->delivery_charge;
            $order->payment_type = $request->payment_type;
            $order->save();
            $price = 0;
            $delivery_charge =0;
            foreach($cart as $c){
                if($c->discount>0){
                    if($c->discount_type === "flat"){
                        $new_price = $c->price - $c->discount;
                    }else{
                        $new_price =$c->price - ($c->price*$c->discount)/100; 
                    }
                }else{
                    $new_price = $c->price;
                }
                $price = round($price + ($new_price * $c->quantity)); 
                
                DB::table('order_details')->insert([
                    'order_id'=> $order->id,
                    'product_id' => $c->product_id,
                    'variant_id' => $c->variant_id,
                    'quantity' => $c->quantity,
                    'price' => $new_price
                ]);  
            }
            $order->update([
                'total_price'=> $price,
            ]);
            DB::commit();
            Cart::where('user_id',auth()->id())->delete();
            return response()->json([
                'message'=> 'Order completed successfully.'
            ],200);

            
            
            }else{
                return response()->json([
                    'message'=> 'Cart is empty',
                ],500);
            }
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'message'=> 'something went wrong',
                'error'  => $e->getMessage(),
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
