<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'author_id',
        'reviewed_user_id',
        'rating',
        'comment'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewedUser()
    {
        return $this->belongsTo(User::class, 'reviewed_user_id');
    }
}