<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Campos Mercado Pago
            $table->string('mp_preference_id')->nullable()->after('transaction_id');
            $table->string('mp_payment_id')->nullable()->after('mp_preference_id');
            $table->string('mp_status')->nullable()->after('mp_payment_id');
            $table->string('mp_status_detail')->nullable()->after('mp_status');
            $table->string('mp_payment_method')->nullable()->after('mp_status_detail');
            $table->integer('mp_installments')->nullable()->after('mp_payment_method');
            $table->decimal('mp_transaction_amount', 10, 2)->nullable()->after('mp_installments');
            $table->json('metadata')->nullable()->after('mp_transaction_amount');
            
            // Actualizar campos existentes para compatibilidad
            $table->string('transaction_id')->nullable()->change();
            $table->string('payment_method')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'mp_preference_id',
                'mp_payment_id',
                'mp_status',
                'mp_status_detail',
                'mp_payment_method',
                'mp_installments',
                'mp_transaction_amount',
                'metadata'
            ]);
        });
    }
};