<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'content',
        'parent_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Question::class, 'parent_id');
    }

    public function answers()
    {
        return $this->hasMany(Question::class, 'parent_id');
    }
    public function replies()
{
    return $this->hasMany(Question::class, 'parent_id');
}
    
public function scopeRootQuestions($query)
{
    return $query->whereNull('parent_id');
}
}