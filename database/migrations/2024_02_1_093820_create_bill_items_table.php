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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("qty")->nullable();
            $table->string("expire_date")->nullable();
            $table->string("bouns")->nullable();
            $table->string("current_qty")->nullable();
            $table->string("sell_price")->nullable();
            $table->string("buy_price")->nullable();

            $table->string("tax")->nullable();
            $table->string("discount")->nullable();
            $table->string("total_price_buy")->nullable();
            $table->string("total_price_sell")->nullable();

            $table->string("status")->nullable();

            $table->string("code")->nullable();

            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unsignedBigInteger('re_buy_detail_id')->nullable();
            $table->foreign('re_buy_detail_id')->references('id')->on('re_buy_details')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
