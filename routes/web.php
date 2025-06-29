<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class);
Route::resource('sales', SaleController::class);
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');