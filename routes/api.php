<?php
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\PersonaApiController;
use Illuminate\Support\Facades\Route;

// Rutas de Facturas
Route::get('/invoices', [InvoiceApiController::class, 'index']);
Route::get('/invoices/{id}', [InvoiceApiController::class, 'show']);
Route::post('/invoices', [InvoiceApiController::class, 'store']);
Route::delete('/invoices/{id}', [InvoiceApiController::class, 'destroy']);

// Rutas de Clientes
Route::get('/personas', [PersonaApiController::class, 'index']);
Route::get('/personas/{id}', [PersonaApiController::class, 'show']);
Route::post('/personas', [PersonaApiController::class, 'store']);
Route::put('/personas/{id}', [PersonaApiController::class, 'update']);
Route::delete('/personas/{id}', [PersonaApiController::class, 'destroy']);
