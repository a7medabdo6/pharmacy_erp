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
        Schema::create('sale_point_operations', function (Blueprint $table) {
            $table->id();
            $table->enum("type", ['سحب رصيد', "توريد رصيد",]);
            $table->enum("bill_type", ['فاتورة مبيعات ', "فاتورة شرا", "رصيد اول", " مرتجع مبيعات", " مرتجع فاتورة شرا"]);
            $table->string("balance")->nullable();
            $table->string("amount_of_money")->nullable();


            $table->unsignedBigInteger('bill_id');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('sale_point_operations');
    }
};
