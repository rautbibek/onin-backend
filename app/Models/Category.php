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
        'icon',
        'cover'
    ];
    protected $dates = ['deleted_at'];

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
}
