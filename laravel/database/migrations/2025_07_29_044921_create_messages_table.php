<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // Relación al chat
            $table->foreignId('chat_id')
                  ->constrained('chats')
                  ->onDelete('cascade');
            // Quién envía el mensaje
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            // Cuerpo del mensaje
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
