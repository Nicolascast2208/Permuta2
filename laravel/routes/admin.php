<?php
// routes/admin.php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Usuarios
Route::resource('users', UserController::class);
Route::post('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
Route::post('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Productos
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/approve', [ProductController::class, 'approve'])->name('products.approve');
    Route::post('products/{product}/reject', [ProductController::class, 'reject'])->name('products.reject');
    Route::post('products/{product}/toggle-published', [ProductController::class, 'togglePublished'])->name('products.toggle-published');
    
    // Categorías
    Route::resource('categories', CategoryController::class);
    
    // Ofertas
    Route::resource('offers', OfferController::class);
    Route::post('offers/{offer}/accept', [OfferController::class, 'accept'])->name('offers.accept');
    Route::post('offers/{offer}/reject', [OfferController::class, 'reject'])->name('offers.reject');
    Route::post('offers/{offer}/update-payment-status', [OfferController::class, 'updatePaymentStatus'])->name('offers.update-payment-status');
    
    // Chats
    Route::resource('chats', ChatController::class);
    Route::post('chats/{chat}/close', [ChatController::class, 'close'])->name('chats.close');
    Route::get('chats/{chat}/messages', [ChatController::class, 'messages'])->name('chats.messages');
    
    // Preguntas
    Route::resource('questions', QuestionController::class);
    Route::post('questions/{question}/answer', [QuestionController::class, 'answer'])->name('questions.answer');
    
    // Reseñas
    Route::resource('reviews', ReviewController::class);
    Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    
    // Notificaciones
    Route::resource('notifications', NotificationController::class);
    Route::post('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Pagos
Route::resource('payments', PaymentController::class);
Route::get('payments/user/{user}', [PaymentController::class, 'byUser'])->name('payments.by-user');

// Reportes
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

});