<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id','image'];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

 public function children()
{
    return $this->hasMany(Category::class, 'parent_id')->orderBy('name', 'asc');
}

public function scopeOrdered($query)
{
    return $query->orderBy('name', 'asc');
}

    // Para obtener solo categorías principales (sin padre)
    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    // Para obtener categorías con sus subcategorías
    public function scopeWithChildren($query)
    {
        return $query->with('children');
    }
}