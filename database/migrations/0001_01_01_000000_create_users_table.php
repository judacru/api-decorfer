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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamp('createdat')->useCurrent();
            $table->timestamp('updatedat')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('user');
            $table->unsignedBigInteger('idrole');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('active')->default(true);
            $table->boolean('system')->default(false);
            $table->timestamp('createdat')->useCurrent();
            $table->timestamp('updatedat')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('idrole')->references('id')->on('roles');
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('cellphone');
            $table->string('address');
            $table->boolean('active')->default(true);
            $table->boolean('special')->default(false);
            $table->string('identification');
            $table->unsignedBigInteger('createdby');
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->timestamp('createdat')->useCurrent();
            $table->timestamp('updatedat')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('createdby')->references('id')->on('users');
            $table->foreign('updatedby')->references('id')->on('users');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('idcustomer')->nullable();
            $table->foreign('idcustomer')->references('id')->on('customers');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->float('price');
            $table->boolean('active')->default(true);
            $table->float('minimunvalue');
            $table->unsignedBigInteger('createdby');
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->timestamp('createdat')->useCurrent();
            $table->timestamp('updatedat')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('createdby')->references('id')->on('users');
            $table->foreign('updatedby')->references('id')->on('users');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('users');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
