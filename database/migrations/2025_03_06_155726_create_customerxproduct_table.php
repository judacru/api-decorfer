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
        Schema::create('customerxproduct', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idcustomer');
            $table->unsignedBigInteger('idproduct');
            $table->float('price');
            $table->unsignedBigInteger('createdby');
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->timestamp('createdat')->useCurrent();
            $table->timestamp('updatedat')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('createdby')->references('id')->on('users');
            $table->foreign('updatedby')->references('id')->on('users');
            $table->foreign('idcustomer')->references('id')->on('customers');
            $table->foreign('idproduct')->references('id')->on('products');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('applyminimum')->default(true);
            $table->dropColumn('minimunvalue');
        });

        Schema::table('remissiondetail', function (Blueprint $table) {
            $table->string('reference')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customerxproduct');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('applyminimum');
            $table->float('minimunvalue');
        });
    }
};
