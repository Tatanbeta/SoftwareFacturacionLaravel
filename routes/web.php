<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PersonaController;

Route::get('/', function () {
    return redirect()->route('invoices.index');
});

Route::get('/invoices/{id}/pdf', [InvoiceController::class, 'pdf'])
    ->name('invoices.pdf');

Route::resource('invoices', InvoiceController::class);
Route::resource('personas', PersonaController::class);