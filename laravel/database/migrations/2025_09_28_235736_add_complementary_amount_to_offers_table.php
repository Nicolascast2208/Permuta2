<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->decimal('complementary_amount', 10, 2)->default(0)->after('product_requested_id');
            $table->text('comment')->nullable()->after('complementary_amount');
        });
    }

    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('complementary_amount');
            $table->dropColumn('comment');
        });
    }
};