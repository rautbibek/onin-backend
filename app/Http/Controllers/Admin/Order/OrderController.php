<?php

namespace App\Http\Controllers\Admin\Order;
use App\Models\Order;
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
               ->select('orders.*','users.name as order_by');
        if(isset($filter)){
            //return $filter;
            $order = $order->where('orders.status',$filter);
        }
        $order = Datatable::filter($order,['orders.order_identifier','users.name','orders.payment_status','payment_type']);
        return  OrderResource::collection($order)->response()
        ->setStatusCode(200);
    }
}
