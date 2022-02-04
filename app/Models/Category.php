<?php

namespace App\Models;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,HasSlug,SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'meta_title',
        'is_featured',
        'last_child',
        'status',
        'lvl',
        'icon',
        'cover'
    ];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'has_color' => 'boolean',
        'has_size'  => 'boolean',
        'status'    => 'boolean',
        'last_child'=> 'boolean'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function children()
    {
        return $this->hasMany(Category::class,'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function categoryOptions(){
        return $this->hasMany(CategoryOption::class);
    }

    public function options(){
        return $this->belongsToMany(Option::class,'category_options');
    }

    public function brand(){
        return $this->hasMany(Brand::class);
    }
}
