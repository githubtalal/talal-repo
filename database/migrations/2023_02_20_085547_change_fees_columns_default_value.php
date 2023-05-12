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
        Schema::table('products', function (Blueprint $table) {
            $table->float('tax_amount')->default(0)->change();
            $table->float('fees_amount')->default(0)->change();
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->float('fees_amount')->default(0)->change();
            $table->float('tax_amount')->default(0)->change();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->float('fees_amount')->default(0)->change();
            $table->float('tax_amount')->default(0)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->float('fees_amount')->default(0)->change();
            $table->float('tax_amount')->default(0)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->float('fees_amount')->default(0)->change();
            $table->float('tax_amount')->default(0)->change();
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
