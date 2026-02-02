<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas para las sugerencias de productos
Route::get('/products/{product}/suggestions', [ProductController::class, 'getEnhancedSuggestions']);
Route::get('/products/{product}', [ProductController::class, 'apiShow']);

