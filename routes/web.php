<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

// Set the home route '/' to open your Invoice Creation Form
Route::get('/', [InvoiceController::class, 'create'])->name('invoice.create');

// Form submission route
Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');

// Public view & PDF routes
Route::get('/i/{uuid}', [InvoiceController::class, 'showPublic'])->name('invoice.public');
Route::get('/i/{uuid}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoice.download');


/*
Route::get('/', function () {
    return view('welcome'); */
