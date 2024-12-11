<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\EntidadController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\RemateController;
use App\Http\Controllers\FacturaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(BancoController::class)->prefix('bancos')->group(function () {
    Route::get('/', 'index');
    Route::get('/{codnum}', 'show');
    Route::post('/', 'store');
    Route::put('/{codnum}', 'update');
    Route::delete('/{codnum}', 'destroy');
});

Route::controller(EntidadController::class)->prefix('entidades')->group(function () {
    Route::get('/', 'index');
    Route::get('/{cuit}', 'show');
    Route::post('/', 'store');
    Route::put('/{codnum}', 'update');
    Route::delete('/{codnum}', 'destroy');
});

Route::controller(LoteController::class)->prefix('lotes')->group(function () {
    Route::get('/', 'index');
    Route::get('/{codnum}', 'show');
    Route::post('/', 'store');
    Route::put('/{codnum}', 'update');
    Route::delete('/{codnum}', 'destroy');
});

Route::controller(RemateController::class)->prefix('remates')->group(function () {
    Route::get('/', 'index');
    Route::get('/{ncomp}', 'show');
    Route::get('/{codrem}/lotes', 'obtenerLotes');
    Route::post('/', 'store');
    Route::put('/{codnum}', 'update');
    Route::delete('/{codnum}', 'destroy');
});

Route::controller(FacturaController::class)->prefix('factura')->group(function () {
    Route::post('/', 'facturar');
});
