<?php

namespace App\Http\Controllers\Api\V1\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\OrderResource;
use App\Mail\OrderCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $order = Order::where('user_id',Auth::id())
        ->with('orderDetail',function($q){
            $q->leftJoin('products','order_details.product_id','products.id')
              ->leftJoin('variants','order_details.variant_id','variants.id')
              ->select('order_details.*','products.id as product_id','products.title','products.slug','products.cover','variants.color');
        });
        //$order = $order->where('status',3);
        if(isset($status)){
            $order = $order->where('status',$status);
        }
        $order = $order->latest()->paginate(20);
        return OrderResource::collection($order);
        
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
                       'variants.price',
                       'variants.sold'
                       )
                   ->where('carts.user_id',auth()->id())
                   ->get();
        
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

                
                $order_detail = DB::table('order_details')->insert([
                    'order_id'=> $order->id,
                    'product_id' => $c->product_id,
                    'variant_id' => $c->variant_id,
                    'quantity' => $c->quantity,
                    'price' => $new_price
                ]);

                DB::table('variants')->where('id',$c->variant_id)->update([
                    'quantity'=> $c->stock - $c->quantity,
                    'sold'=> $c->sold + $c->quantity
                ]);  
            }
            $order->update([
                'total_price'=> $price,
            ]);

            $admin = Admin::all();
            //Mail::to('rautbibek47@gmail.com')->queue(new OrderCompleted());
            Notification::send($admin,new NewOrderNotification($order));
            DB::commit();
            Cart::where('user_id',auth()->id())->delete();
            return response()->json([
                'message'=> 'Order completed successfully.',
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
        $order = Order::where('user_id',Auth::id())->where('order_identifier',$id)
        ->with('orderDetail',function($q){
            $q->leftJoin('products','order_details.product_id','products.id')
              ->leftJoin('variants','order_details.variant_id','variants.id')
              ->select('order_details.*','products.title','products.slug','products.cover','variants.color');
        })->first();
        if($order){
            return new OrderResource($order);
        }
        return response()->json([
            'message'=> 'No match found for '.$id.'.',
        ]);
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
