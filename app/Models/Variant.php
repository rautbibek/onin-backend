<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'color',
        'sizes',
        'quantity',
        'sku',
        'price',
        'special_price',
        'extra'
    ];

    protected $casts = [
        'sizes'=>'array',
    ];

    

    public function scopePriceFilter($query,$min,$max)
    {
        return $query->where('price', '>', $min)->where('price','<',$max);
    }
}
