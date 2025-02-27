<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RemissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticationController::class)
    ->prefix('auth')
    ->group(function () {
        Route::post('/login', 'login');
        Route::middleware(['auth:sanctum'])->post('/logout', 'logout');
    });

Route::controller(UserController::class)
    ->prefix('users')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', 'findAll');
        Route::get('/{id}', 'detail');
        Route::post('/', 'create');
        Route::put('/{id}', 'update');
    });

Route::controller(ProductController::class)
    ->prefix('products')
    ->group(function () {
        Route::get('/', 'findAll');
        Route::get('/{id}', 'detail');
    });

Route::controller(ProductController::class)
    ->prefix('products')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/', 'create');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'inactivate');
    });

Route::controller(CustomerController::class)
    ->prefix('customers')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', 'findAll');
        Route::get('/{id}', 'detail');
        Route::post('/', 'create');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'inactivate');
    });

Route::controller(RemissionController::class)
    ->prefix('invoices')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', 'findAll');
        Route::get('/consecutive', 'consecutive');
        Route::get('/reprint/{id}', 'generatePDF');
        Route::get('/{id}', 'detail');
        Route::post('/', 'create');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'inactivate');
    });

Route::get('/test-email', function () {
    Mail::raw('Este es un correo de prueba desde Laravel con Hostinger.', function ($message) {
        $message->to('destinatario@example.com')
                ->subject('Prueba de correo');
    });

    return 'Correo enviado';
});
