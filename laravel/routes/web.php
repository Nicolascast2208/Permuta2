<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CheckoutController; 
use App\Http\Controllers\ChatController; 
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\IntermediateQuestionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
require __DIR__.'/admin.php';
// Authentication Routes
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


// Vista que muestra verify-email.blade.php
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Ruta que el usuario abre desde su correo
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard'); // o donde quieras redirigir
})->middleware(['auth', 'signed'])->name('verification.verify');

// Para reenviar el correo
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Rutas de recuperación de contraseña
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');
// Rutas de categorías
Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categorias/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/terminos-y-condiciones', function () {
    return view('terms');
})->name('terms');

Route::get('/privacidad', function () {
    return view('privacy');
})->name('privacy');

Route::get('/como-permutar', function () {
    return view('como-permutar');
})->name('como.permutar');
route::get('/nosotros',function(){    return view('nosotros');
})->name('nosotros');
Route::get('/consejos-de-seguridad', function () {
    return view('consejos-de-seguridad');
})->name('consejos.seguridad');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Ruta para el componente Livewire
Route::get('/categorias-filtro/{slug?}', \App\Livewire\Categories::class)->name('categories.filter');

 Route::get('/user/{user}', [UserController::class, 'showProfile'])->name('user.profile');
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'results'])->name('search.results'); 

    // Listado de todos los usuarios/permutadores
Route::get('/permutadores', [UserController::class, 'index'])->name('users.index');
// Protected Routes
Route::middleware(['auth'])->group(function () {
// Rutas para preguntas intermedias
Route::post('/oferta/{offer}/pregunta-intermedia', [IntermediateQuestionController::class, 'store'])
    ->name('intermediate.questions.store')
    ->middleware('auth');

Route::post('/pregunta-intermedia/{question}/responder', [IntermediateQuestionController::class, 'answer'])
    ->name('intermediate.questions.answer')
    ->middleware('auth');
    //notificaciones
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/mis-productos', [DashboardController::class, 'myProducts'])->name('dashboard.my-products');
    Route::get('/dashboard/ofertas-recibidas', [DashboardController::class, 'receivedOffers'])->name('dashboard.received-offers');
    Route::get('/dashboard/my-products', [ProductController::class, 'myProducts'])->name('dashboard.my-products-test')->middleware('auth');
    Route::get('/dashboard/ofertas-enviadas', [DashboardController::class, 'sentOffers'])->name('dashboard.sent-offers');
    Route::get('/dashboard/preguntas', [DashboardController::class, 'questions'])->name('dashboard.questions');
        // Perfil
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil/actualizar', [ProfileController::class, 'update'])->name('profile.update');
    // Products
Route::get('/crear-producto', function () {
    return app()->call([app()->make(App\Http\Controllers\ProductController::class), 'create']);
})->name('products.createx');
Route::get('/offers/success/{offer}', [OfferController::class, 'success'])->name('offers.success');
Route::get('/ofertar/{product}', [OfferController::class, 'create'])->name('offers.create')->middleware('auth');
Route::post('/get-user-location', [ProductController::class, 'getUserLocation'])
    ->name('user.location');
    Route::post('/save-location', function (Request $request) {
    Session::put('user_location', [
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'accuracy' => $request->accuracy
    ]);
    
    return response()->json(['success' => true]);
})->name('save.location');
    Route::post('/productos', [ProductController::class, 'store'])->name('products.store');
    Route::get('/productos/{product}/editar', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/productos/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/productos/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
       Route::get('/productos/crear', [ProductController::class, 'create'])->name('products.create');
    Route::get('/checkout/{product}', [CheckoutController::class, 'show'])
    ->name('checkout.show')
    ->middleware('auth');
Route::get('/products/{product}/suggestions', [ProductController::class, 'showSuggestions'])->name('products.suggestions');
    Route::get('/checkout/{product}', [CheckoutController::class, 'show'])
        ->name('checkout.show');
    Route::get('/checkout/commission/{offer}', [CheckoutController::class, 'showCommission'])
     ->name('checkout.commission');
Route::get('/oferta/{offer}/intermediar', [OfferController::class, 'intermediate'])->name('offer.intermediate')->middleware('auth');
// Procesar pago de comisión
Route::post('/checkout/process-commission/{offer}', [CheckoutController::class, 'processCommission'])
     ->name('checkout.process-commission');
    Route::post('/checkout/{product}/process', [CheckoutController::class, 'processPayment'])
        ->name('checkout.process');
    // Payments
    Route::get('/pagar/publicacion/{product}', [PaymentController::class, 'init'])->name('payment.init');
    Route::get('/pagar/comision/{offer}', [PaymentController::class, 'commission'])->name('payment.commission');
    // Para mostrar el formulario de pago
Route::get('/checkout/commission-offered/{offer}', [CheckoutController::class, 'showCommissionOffered'])
    ->name('checkout.commission-offered');

// Para procesar el pago
Route::post('/checkout/commission-offered/{offer}/process', [CheckoutController::class, 'processCommissionOffered'])
    ->name('checkout.process-commission-offered');
    Route::match(['get', 'post'], '/pagar/retorno', [PaymentController::class, 'return'])
     ->name('payment.return');

    Route::post('/pagar/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
Route::post('/notifications/mark-multiple-read', [NotificationController::class, 'markMultipleAsRead'])
    ->name('notifications.markMultipleAsRead')
    ->middleware('auth');
    // Offers
    Route::post('/ofertas', [OfferController::class, 'store'])->name('offers.store');
Route::post('/ofertas/{offer}/aceptar', [OfferController::class, 'accept'])->name('offers.accept');
Route::post('/ofertas/{offer}/rechazar', [OfferController::class, 'reject'])->name('offers.reject');
        // Permutas
    Route::get('/dashboard/permutas', [DashboardController::class, 'trades'])
        ->name('dashboard.trades');
Route::get('/chat/{chat}', [ChatController::class, 'show'])
    ->name('chat.show')
    ->middleware('auth');
Route::get('/chats/{chat}/review', [ReviewController::class, 'create'])
    ->name('reviews.create')
    ->middleware('auth');
Route::post('/chats/{chat}/complete', [ReviewController::class, 'create'])
    ->name('chats.complete');
Route::post('/chats/{chat}/review', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware('auth');
Route::get('/pago-exitoso', [PaymentController::class, 'success'])
    ->name('payment.success');

Route::get('/pago-fallido', [PaymentController::class, 'failure'])
    ->name('payment.failure');

Route::get('/offers/{offer}/details', function (App\Models\Offer $offer) {
    // Verificar que el usuario tiene permiso para ver esta oferta
    if (auth()->id() !== $offer->to_user_id && auth()->id() !== $offer->from_user_id) {
        abort(403);
    }

    $offer->load([
        'productRequested.images',
        'productsOffered.images',
        'fromUser',
        'toUser',
        'intermediateQuestions.user',
        'intermediateQuestions.answerer',
        'chat'
    ]);

    // Determinar si el usuario actual es el que envió la oferta
    $isSender = auth()->id() === $offer->from_user_id;

    // CALCULAR LOS VALORES
    $dashboardController = new App\Http\Controllers\DashboardController();
    $matchScore = $dashboardController->calculateOfferMatchScore($offer);
    $totalOfferedValue = $dashboardController->calculateTotalOfferedValue($offer);

    // DEBUG: Log para verificar valores
    \Log::info('Offer Details Debug', [
        'offer_id' => $offer->id,
        'product_requested_price' => $offer->productRequested->price_reference,
        'products_offered_count' => $offer->productsOffered->count(),
        'products_offered_prices' => $offer->productsOffered->pluck('price_reference'),
        'complementary_amount' => $offer->complementary_amount,
        'calculated_total_offered' => $totalOfferedValue,
        'match_score' => $matchScore
    ]);

    // Preparar datos para JSON
    $data = [
        'id' => $offer->id,
        'status' => $offer->status,
        'status_name' => $offer->status_name,
        'comment' => $offer->comment,
        'complementary_amount' => $offer->complementary_amount,
        'created_at' => $offer->created_at,
        'from_user_id' => $offer->from_user_id,
        'from_user' => [
            'name' => $offer->fromUser->name,
        ],
        'to_user' => [
            'name' => $offer->toUser->name,
        ],
        'product_requested' => [
            'id' => $offer->productRequested->id,
            'title' => $offer->productRequested->title,
            'description' => $offer->productRequested->description,
            'price_reference' => $offer->productRequested->price_reference,
            'condition_name' => $offer->productRequested->condition_name,
            'location' => $offer->productRequested->location,
            'first_image_url' => $offer->productRequested->images->count() > 0 
                ? asset('storage/' . $offer->productRequested->images->first()->path)
                : asset('images/default-product.png')
        ],
        'products_offered' => $offer->productsOffered->map(function($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'price_reference' => $product->price_reference,
                'condition_name' => $product->condition_name,
                'first_image_url' => $product->images->count() > 0 
                    ? asset('storage/' . $product->images->first()->path)
                    : asset('images/default-product.png')
            ];
        }),
        'intermediate_questions' => $offer->intermediateQuestions->map(function($question) {
            return [
                'question' => $question->question,
                'answer' => $question->answer,
                'created_at' => $question->created_at,
                'user' => [
                    'name' => $question->user->name,
                    'profile_photo_url' => $question->user->profile_photo_url
                ],
                'answerer' => $question->answerer ? [
                    'name' => $question->answerer->name,
                    'profile_photo_url' => $question->answerer->profile_photo_url
                ] : null
            ];
        }),
        'has_accepted_offer_for_product' => \App\Models\Offer::hasAcceptedOfferForProduct($offer->product_requested_id),
        'chat' => $offer->chat ? ['id' => $offer->chat->id] : null,
        'is_sender' => $isSender,
        
        // AGREGAR ESTOS CAMPOS CALCULADOS
        'match_score' => $matchScore,
        'total_offered_value' => $totalOfferedValue
    ];

    return response()->json($data);
})->middleware('auth');

    
  Route::get('/contacto', function() {
    return view('contact');
})->name('contact');


});