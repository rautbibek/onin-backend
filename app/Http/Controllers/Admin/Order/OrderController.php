<?php

namespace App\Http\Controllers\Admin\Order;
use App\Models\Order;
use App\Models\orderDetail;
use Illuminate\Support\Facades\DB;
use App\Helper\Datatable;
use App\Http\Resources\Admin\OrderResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        $filter = $request->get('filter_status')?$request->get('filter_status'):null;
        $order = Order::withCount('orderDetail')
               ->join('users','users.id','=','orders.user_id')
               ->select('orders.*','users.name as order_by')->latest();
        if(isset($filter)){
            //return $filter;
            $order = $order->where('orders.status',$filter);
        }
        $order = Datatable::filter($order,['orders.order_identifier','users.name','orders.payment_status','payment_type']);
        return  OrderResource::collection($order)->response()
        ->setStatusCode(200);
    }

    public function orderDetail($id){
        $order = Order::leftJoin('users','users.id','orders.user_id')
        ->select('orders.*','users.name as order_by','users.email','users.mobile as contact_number')
        ->where('orders.id',$id)->with('orderDetail',function($q){
            $q->leftJoin('products','order_details.product_id','products.id')
              ->leftJoin('variants','order_details.variant_id','variants.id')
               ->select(
                   'order_details.*',
                   'products.title',
                   'products.cover',
                   'variants.color as variant_color',
                   'variants.sku'
            );
        })->first();
        return response()->json($order);
    }

    public function updateComment(Request $request,$id){
        $this->validate($request,[
            'comment' => 'required',
        ]);
        $order = Order::findOrFail($id);
        $order->comment = $request->comment;
        $order->update();
        return response()->json([
            'message'=> 'Comment updated succefully'
        ],200);
    }

    public function changePaymentStatus($id){
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status'=> true,
        ]);
        return response()->json([
            'message'=> 'Payment status changed to completed succefully'
        ]);
    }
}
