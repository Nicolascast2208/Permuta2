<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'buy_order',
        'session_id',
        'amount',
        'status',
        'type',
        'payment_method',
        'response_data',
        'authorization_code',
        'card_number',
        'card_type',
        'transaction_date',
        'user_id',
        'product_id',
        'offer_id',
        // Campos Mercado Pago
        'mp_preference_id',
        'mp_payment_id',
        'mp_status',
        'mp_status_detail',
        'mp_payment_method',
        'mp_installments',
        'mp_transaction_amount',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'mp_transaction_amount' => 'decimal:2',
        'transaction_date' => 'datetime',
        'response_data' => 'array',
        'metadata' => 'array'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByMercadoPago($query)
    {
        return $query->whereNotNull('mp_preference_id');
    }

    // Accesores
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedTypeAttribute()
    {
        return [
            'publication' => 'Publicación',
            'commission_requested' => 'Comisión Producto Solicitado',
            'commission_offered' => 'Comisión Productos Ofrecidos'
        ][$this->type] ?? $this->type;
    }

    public function getFormattedStatusAttribute()
    {
        return [
            'pending' => 'Pendiente',
            'approved' => 'Aprobado',
            'failed' => 'Fallido',
            'cancelled' => 'Cancelado'
        ][$this->status] ?? $this->status;
    }

    public function getIsSuccessfulAttribute()
    {
        return $this->status === 'approved';
    }

    // Métodos de negocio - Mercado Pago
    public function markAsMpApproved($mpData)
    {
        $this->update([
            'status' => 'approved',
            'mp_payment_id' => $mpData['mp_payment_id'] ?? null,
            'mp_status' => $mpData['status'] ?? null,
            'mp_status_detail' => $mpData['status_detail'] ?? null,
            'mp_payment_method' => $mpData['payment_method'] ?? null,
            'mp_installments' => $mpData['installments'] ?? null,
            'mp_transaction_amount' => $mpData['transaction_amount'] ?? null,
            'transaction_date' => now(),
            'payment_method' => 'mercadopago',
            'response_data' => $mpData
        ]);
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'mp_status_detail' => $reason,
            'response_data' => ['error' => $reason]
        ]);
    }

    public function markAsPending()
    {
        $this->update(['status' => 'pending']);
    }

    // Métodos auxiliares
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
}