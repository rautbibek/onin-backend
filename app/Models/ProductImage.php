<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable=[
        'product_id',
        'file',
        'size',
        'tags'
    ];

    public static function boot(){
       parent::boot();
       static::deleting(function($image){
            Storage::delete('/product/'.$image->file);
            Storage::delete('/thumb/'.$image->file);
        });
    }
    
}
