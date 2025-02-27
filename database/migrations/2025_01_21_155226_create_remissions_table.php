<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('remissions', function (Blueprint $table) {
            $table->id();
            $table->string('consecutive')->unique();
            $table->unsignedBigInteger('idcustomer');
            $table->float('total');
            $table->float('totalpackages');
            $table->unsignedBigInteger('createdby');
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->timestamp('createdat')->useCurrent();
            $table->timestamp('updatedat')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('createdby')->references('id')->on('users');
            $table->foreign('updatedby')->references('id')->on('users');
            $table->foreign('idcustomer')->references('id')->on('customers');
        });

        Schema::create('remissiondetail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idremission');
            $table->unsignedBigInteger('idproduct');
            $table->string('reference');
            $table->integer('quantity');
            $table->integer('colors');
            $table->integer('packages');
            $table->float('price');
            $table->float('total');
            $table->boolean('minimum');
            $table->unsignedBigInteger('createdby');
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->timestamp('createdat')->useCurrent();
            $table->timestamp('updatedat')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('createdby')->references('id')->on('users');
            $table->foreign('updatedby')->references('id')->on('users');
            $table->foreign('idproduct')->references('id')->on('products');
            $table->foreign('idremission')->references('id')->on('remissions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remissiondetails');
        Schema::dropIfExists('remissions');
    }
};
