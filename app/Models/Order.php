<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'order_identifier',
        'visibility',
        'delivery_charge',
        'total_price',
        'delivery_address',
        'payment_type',
        'payment_status',
        'return_status',
        'refund_status',
        'status',
        'comment',
    ];

    public function orderDetail(){
        return $this->hasMany(OrderDetail::class);
    }

    protected $casts = [
        
        'status' => 'integer'
    ];
}

