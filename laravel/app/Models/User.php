<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomResetPassword;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'rut',
        'email',
        'password',
        'alias',
        'rating',
        'profile_photo_path',
        'role',
        'is_active',
        'deactivated_at',
        'deactivation_reason'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'rating' => 'float',
        'is_active' => 'boolean',
        'deactivated_at' => 'datetime'
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            // Generar alias único
            do {
                $alias = 'Permutador_' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            } while (self::where('alias', $alias)->exists());
            
            $user->alias = $alias;
            
            // Asignar foto de perfil por defecto si no se proporciona
            if (empty($user->profile_photo_path)) {
                $user->profile_photo_path = 'default-profile.png';
            }
        });

        // Cuando un usuario es desactivado, ocultar sus productos
        static::updated(function ($user) {
            if ($user->isDirty('is_active') && !$user->is_active) {
                $user->products()->update(['published' => false]);
            }
        });
    }

    // Relaciones
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('published', true);
    }

    public function sentOffers()
    {
        return $this->hasMany(Offer::class, 'from_user_id');
    }

    public function receivedOffers()
    {
        return $this->hasMany(Offer::class, 'to_user_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Relación con reseñas
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('read', false);
    }

    public function updateRating()
    {
        $this->rating = $this->reviews()->avg('rating');
        $this->save();
    }

    public function completedPermutas()
    {
        return $this->hasMany(Product::class)->where('status', 'paired');
    }

    public function unreadReceivedOffers(): HasMany
    {
        return $this->hasMany(Offer::class, 'to_user_id')
                    ->where('read_by_receiver', false);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Métodos para el rol de administrador
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Métodos para estado activo/inactivo
    public function isActive()
    {
        return $this->is_active;
    }

    public function isInactive()
    {
        return !$this->is_active;
    }

    public function deactivate($reason = null)
    {
        $this->update([
            'is_active' => false,
            'deactivated_at' => now(),
            'deactivation_reason' => $reason
        ]);

        // Ocultar todos los productos del usuario
        $this->products()->update(['published' => false]);

        return $this;
    }

    public function activate()
    {
        $this->update([
            'is_active' => true,
            'deactivated_at' => null,
            'deactivation_reason' => null
        ]);

        // Reactivar productos (solo los que no estén permutados)
        $this->products()
            ->where('status', '!=', 'paired')
            ->update(['published' => true]);

        return $this;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Accesores
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path 
            ? asset('storage/' . $this->profile_photo_path)
            : asset('images/default-profile.png');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 5.0;
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_active) {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Activo</span>';
        } else {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactivo</span>';
        }
    }

    public function getDeactivationPeriodAttribute()
    {
        if ($this->deactivated_at) {
            return $this->deactivated_at->diffForHumans();
        }
        return null;
    }
        public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
    
       public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

}