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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("nameAr")->nullable();
            $table->string("nameEn")->nullable();

            $table->string("company")->nullable();
            $table->string("ac")->nullable();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');


            $table->unsignedBigInteger('large_unit_id');
            $table->foreign('large_unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->string("large_unit_no")->nullable();
            $table->string("large_unit_price")->nullable();
            $table->string("large_unit_qty")->nullable();

            $table->unsignedBigInteger('small_unit_id');
            $table->foreign('small_unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->string("small_unit_price")->nullable();
            $table->string("small_unit_qty")->nullable();
            $table->string("small_unit_no")->nullable();

            $table->unsignedBigInteger('medium_unit_id');
            $table->foreign('medium_unit_id')->references('id')->on('units')->onDelete('cascade');

            $table->string("medium_unit_no")->nullable();
            $table->string("medium_unit_price")->nullable();
            $table->string("medium_unit_qty")->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
