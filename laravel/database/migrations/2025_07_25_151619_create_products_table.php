<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('category_id')->constrained();
    $table->string('title');
    $table->decimal('price_reference', 10, 2);
    $table->text('description')->nullable();
    $table->dateTime('publication_date')->default(now());
    $table->dateTime('expiration_date')->default(now()->addDays(30));
    $table->enum('condition', ['new', 'used', 'refurbished']);
    $table->string('tags');
    $table->enum('status', ['pending', 'available', 'paired'])->default('pending');
    $table->boolean('published')->default(false);
    $table->string('location');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
