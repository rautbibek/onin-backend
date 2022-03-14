<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory,HasSlug;

    protected $guarded =[];
    // protected $appends =['is_favorite'];

    protected $casts = [
        'meta_keyword' => 'array',
        'image'=>'array',
        'sizes'=>'array',
        'has_color'=> 'boolean',
        'has_size' => 'boolean',
        'discount' => 'integer'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    

    public function variant(){
        return $this->hasMany(Variant::class)->orderBy('quantity','desc');
    }

    public function firstVariant(){
        return $this->hasOne(Variant::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function collection()
    {
        return $this->belongsToMany(Collection::class)->withTimestamps();
    }

    public function optionValues(){
        return $this->hasMany(OptionValue::class);
    }

    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    public function favorites(){
        return $this->belongsToMany(User::class,'favorite','product_id','user_id')->withTimestamps();
    }

    // public function getIsFavoriteAttribute(){
        
    //     return $this->favorites->where('user_id',Auth::id())->count() > 0;
    // }
}
