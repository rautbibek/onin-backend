<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory; 
    
    protected $fillable =[
        'name',
        'district_id',
        'price'
    ];

    public function singleLocalArea(){
        return $this->hasOne(LocalArea::class);
    }
}
