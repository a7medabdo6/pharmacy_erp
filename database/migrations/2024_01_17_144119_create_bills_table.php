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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string("serial")->autoIncrement()->unique();
            $table->string("supplier_serial")->nullable();

            $table->string("bill_number")->nullable();
            $table->string("bill_date")->nullable();
            $table->string("number_of_products")->nullable();
            $table->string("type_of_bill")->nullable();
            $table->string("descount_percentage")->nullable();
            $table->string("tax")->nullable();
            $table->string("total_price_of_buy")->nullable();
            $table->string("total_price_of_sell")->nullable();
            $table->string("what_paid")->nullable();
            $table->string("what_remainning")->nullable();

            $table->string("expenses")->nullable();
            $table->string("notes")->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->unsignedBigInteger('sale_point_id');
            $table->foreign('sale_point_id')->references('id')->on('sale_points')->onDelete('cascade');

            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
