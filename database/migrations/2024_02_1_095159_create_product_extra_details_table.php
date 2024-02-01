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
        Schema::create('product_extra_details', function (Blueprint $table) {
            $table->id();
            $table->string("limit")->default(5);

            $table->string("expire_date")->nullable();
            $table->string("qty")->nullable();

            $table->unsignedBigInteger('bill_item_id');
            $table->foreign('bill_item_id')->references('id')->on('bill_items')->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

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
        Schema::dropIfExists('product_extra_details');
    }
};
