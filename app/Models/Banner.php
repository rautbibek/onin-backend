<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable =[
        'title',
        'subtitle',
        'link',
        'image',
        'type'
    ];

    // public function getBannerAttribute(){

    // }

}
