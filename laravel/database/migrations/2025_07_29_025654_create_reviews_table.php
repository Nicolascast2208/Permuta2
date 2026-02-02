<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Usuario quién hace la reseña
            $table->foreignId('author_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            // Usuario reseñado
            $table->foreignId('reviewed_user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->tinyInteger('rating');      // 1 a 5
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
