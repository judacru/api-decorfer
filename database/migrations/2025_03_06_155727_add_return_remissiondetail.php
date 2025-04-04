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
        Schema::table('remissiondetail', function (Blueprint $table) {
            $table->integer('return')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('applyminimum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remissiondetail', function (Blueprint $table) {
            $table->dropColumn('return');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('applyminimum')->default(true);
        });
    }
};
