<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'user1_id',
        'user2_id',
        'completed_by_user1',
        'completed_by_user2',
        'is_closed'
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function otherUser(User $user)
    {
        return $user->id === $this->user1_id ? $this->user2 : $this->user1;
    }
public function markCompletedByUser($userId)
{
    if ($this->user1_id == $userId) {
        $this->completed_by_user1 = true;
    } elseif ($this->user2_id == $userId) {
        $this->completed_by_user2 = true;
    }

    // Verificar si ambos completaron
    if ($this->completed_by_user1 && $this->completed_by_user2) {
        $this->is_closed = true;
        

    }

    $this->save();
}
}