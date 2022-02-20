<?php

namespace App\Models;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory,HasSlug;
    protected $fillable =[
        'name','discount_type','discount','expire_at','status'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // public function product(){
    //     return $this->hasMany(Product::class,'collection_product','collection_id','product_id');
    // }

    public function product()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
