<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('platform')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_info')->nullable();
            $table->string('notes')->nullable();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->string('platform')->nullable();
            $table->json('additional')->nullable();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('quantity')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
