<?php

namespace App\Http\Controllers\Admin\Order;
use App\Models\OrderDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function orderDetail($id){
        // $order = Order::where('id',$id)->get();
        // return response()->json($order);
    }
}
