<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('admin.Dashboard');
    }

    public function salseReport(){
        $order = DB::table('orders')
                     ->rightJoin('order_details','orders.id','order_details.order_id')
                     ->select(
                         'orders.created_at as date',
                         DB::raw('SUM(quantity) as  TOTAL_SALES')
                         )
                     ->groupBy('order_details.order_id')
                 ->get();
        return response()->json($order);
    }
    public function statusWiseReport(){
        $order = DB::table('order_details')
                     ->join('orders','orders.id','order_details.order_id')
                     ->select(
                        DB::raw("COUNT(CASE WHEN status = 1 THEN 1 END) as PROCESSING"),
                        DB::raw("COUNT(CASE WHEN status = 2 THEN 1 END) as PACKING"),
                        DB::raw("COUNT(CASE WHEN status = 3 THEN 1 END) as READY_TO_DISPATCH"),
                        DB::raw("COUNT(CASE WHEN status = 4 THEN 1 END) as READY_TO_DISPATCH"),
                        DB::raw("COUNT(CASE WHEN status = 5 THEN 1 END) as READY_TO_DISPATCH"),
                        DB::raw("COUNT(CASE WHEN status = 6 THEN 1 END) as READY_TO_DISPATCH"),
                        DB::raw("COUNT(CASE WHEN status = 7 THEN 1 END) as READY_TO_DISPATCH"),
                        DB::raw("COUNT(CASE WHEN status = 8 THEN 1 END) as CANCELLED"),
                    )
                     
                 ->get();
        return response()->json($order);
    }

    public function recordCounter(){
        $users = DB::table('users')->count();
        $products = DB::table('products')->where('status',true)->count();
        $sales = DB::table('orders')->whereIn('status',[1,2,3,4,5])->sum('total_price');
        
        
        return response()->json([
            'total_users' => $users,
            'active_products'=> $products,
            'revenue' =>  $sales
        ]);
    }
}
