<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->references('id')->on('stores');
            $table->foreignId('customer_id')->references('id')->on('customers');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('governorate');
            $table->string('phone_number');
            $table->string('status')->default(\App\Models\Order::PENDING);
            $table->string('payment_id')->nullable();
            $table->string('trx_num')->nullable();
            $table->double('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
