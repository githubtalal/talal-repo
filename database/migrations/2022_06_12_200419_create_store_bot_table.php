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
        Schema::create('store_bots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->references('id')->on('stores');
            $table->string('platform')->comment('ex: Facebook, telegram, etc...');
            $table->string('platform_id')->comment('ex: Facebook page id');
            $table->boolean('active')->default(true);
            $table->string('token')->nullable();
            $table->string('token_type')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('additional')->nullable();
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
        Schema::dropIfExists('store_bot');
    }
};
