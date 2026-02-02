<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'price_reference',
        'description',
        'publication_date',
        'expiration_date',
        'condition',
        'tags',
        'status',
        'published',
        'location',    
        'payment_option', 
        'was_paid',
        'latitude',
        'longitude' 
    ];

    protected $casts = [
        'publication_date' => 'datetime',
        'expiration_date' => 'datetime',
        'published' => 'boolean',
        'was_paid' => 'boolean'
    ];
    
    protected $appends = ['distance'];

    protected static function booted()
    {
        // Scope global para solo mostrar productos de usuarios activos y publicados
        static::addGlobalScope('activeUser', function (Builder $builder) {
            $builder->whereHas('user', function ($query) {
                $query->where('is_active', true);
            });
        });
    }

    public function getDistanceAttribute()
    {
        return $this->attributes['distance'] ?? null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'product_requested_id');
    }

    public function offeredIn(): HasMany
    {
        return $this->hasMany(Offer::class, 'product_offered_id');
    }

    public function getFirstImageUrlAttribute()
    {
        if ($this->images->isNotEmpty()) {
            return asset('storage/' . $this->images->first()->path);
        }
        
        return asset('images/default-product.png');
    }

    public function getConditionNameAttribute()
    {
        return [
            'new' => 'Nuevo',
            'used' => 'Usado',
            'refurbished' => 'Restaurado'
        ][$this->condition] ?? 'Desconocido';
    }

    // Scopes
    public function scopeForListing($query)
    {
        return $query->with(['user', 'category', 'images' => function($q) {
                $q->select('product_id', 'path')->take(1);
            }])
            ->where('status', 'available')
            ->where('published', true)
            ->where('expiration_date', '>', now());
    }

    // Scope para incluir productos de usuarios inactivos (solo para admin)
    public function scopeWithInactiveUsers($query)
    {
        return $query->withoutGlobalScope('activeUser');
    }

    // Método para verificar si el producto es visible
    public function getIsVisibleAttribute()
    {
        return $this->published && 
               $this->user->is_active && 
               $this->status === 'available' &&
               $this->expiration_date > now();
    }
}