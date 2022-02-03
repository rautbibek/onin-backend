<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    // protected $table = ['bt_options'];

    protected $fillable=['kay','code','values'];

    protected $casts = [
        'values' => 'array',
    ];


}
