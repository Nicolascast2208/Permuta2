<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Información básica del pago
            $table->string('transaction_id')->unique()->nullable(); // ID de transacción de Transbank
            $table->string('buy_order'); // Orden de compra
            $table->string('session_id');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'approved', 'failed', 'cancelled'])->default('pending');
            $table->enum('type', ['publication', 'commission_requested', 'commission_offered']);
            $table->string('payment_method')->default('webpay');
            
            // Respuesta de Transbank
            $table->text('response_data')->nullable(); // Respuesta completa en JSON
            $table->string('authorization_code')->nullable();
            $table->string('card_number')->nullable();
            $table->string('card_type')->nullable();
            $table->timestamp('transaction_date')->nullable();
            
            // Relaciones
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('offer_id')->nullable()->constrained()->onDelete('cascade');
            
            // Auditoría
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['user_id', 'status']);
            $table->index(['type', 'status']);
            $table->index('transaction_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};