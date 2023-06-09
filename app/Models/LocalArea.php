<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalArea extends Model
{
    use HasFactory;


    public function singleAddress(){
        return $this->hasOne(Address::class);
    }
}
