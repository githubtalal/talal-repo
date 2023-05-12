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
        Schema::create('store_plan', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('store_id')
                ->references('id')
                ->on('stores');
            $table->foreignId('plan_id')
                ->references('id')
                ->on('plans');
            $table->timestamp('started_at')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('expiry_at')->nullable();
            $table->double('price');
            $table->string('payment_method');
            $table->string('payment_status')->default('pending');
            $table->json('payment_info')->nullable();
            $table->string('discount_code')->nullable();
            $table->double('discount_amount')->nullable()->default(0);
            $table->json('plan_features')->nullable();
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
        Schema::dropIfExists('store_plan');
    }
};
