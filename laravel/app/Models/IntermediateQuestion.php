<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntermediateQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'user_id',
        'question',
        'answer',
        'answered_by'
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answerer()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function getIsAnsweredAttribute()
    {
        return !is_null($this->answer);
    }
}