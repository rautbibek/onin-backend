<?php

namespace App\Models;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory,HasSlug;

    protected $guarded =[];


    protected $casts = [
        'meta_keyword' => 'array',
        'image'=>'array',
        'sizes'=>'array',
        'has_color'=> 'boolean',
        'has_size' => 'boolean'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getCoverAttribute(){
        //
    }

    public function variant(){
        return $this->hasMany(Variant::class);
    }

    public function firstVariant(){
        return $this->hasOne(Variant::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'brand_id');
    }

    public function collection()
    {
        return $this->belongsToMany(Collection::class)->withTimestamps();
    }

    public function optionValues(){
        return $this->hasMany(OptionValue::class);
    }
}
