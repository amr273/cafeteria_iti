<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerWebController;
use App\Http\Controllers\AdminWebController;
use App\Http\Controllers\AiWebController;

// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// كافة المسارات المحمية بتسجيل الدخول (Sanctum)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // التوجيه التلقائي للوحة التحكم حسب صلاحية المستخدم
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('welcome');
    })->name('dashboard');

    // معالجة الذكاء الاصطناعي
    Route::post('/ai/chat-process', [AiWebController::class, 'ask']);

    // ==========================================
    // 1. مسارات العميل (Customer Routes)
    // ==========================================
    Route::middleware('role:customer')->prefix('customer')->group(function () {
        Route::get('/menu', [CustomerWebController::class, 'menu'])->name('customer.menu');

        // التفضيلات
        Route::get('/preferences', [CustomerWebController::class, 'preferences'])->name('customer.preferences');
        Route::post('/preferences', [CustomerWebController::class, 'updatePreferences'])->name('customer.preferences.update');

        // إنشاء وتصفح الطلبات
        Route::post('/orders/store', [CustomerWebController::class, 'storeOrder'])->name('customer.orders.store');
        Route::get('/orders', [CustomerWebController::class, 'myOrders'])->name('customer.orders');

        // التوصيات والشات بوت
        Route::get('/recommendations', [CustomerWebController::class, 'recommendations'])->name('customer.recommendations');
        Route::get('/chatbot', [CustomerWebController::class, 'chatbot'])->name('customer.chatbot');
    });

    // ==========================================
    // 2. مسارات إدارة عناصر السلة والطلب والدفع
    // ==========================================
    // زيادة أو تقليل كمية عنصر + حذف عنصر فردي
    Route::patch('/order-items/{id}/quantity', [CustomerWebController::class, 'updateQuantity'])->name('order-items.update-quantity');
    Route::delete('/order-items/{id}', [CustomerWebController::class, 'removeItem'])->name('order-items.destroy');

    // تفريغ السلة (حذف كافة العناصر)
    Route::delete('/orders/{id}/clear', [CustomerWebController::class, 'clearAll'])->name('orders.clear');

    // صفحة الدفع + معالجة الدفع + إلغاء الطلب
    Route::get('/orders/{id}/checkout', [CustomerWebController::class, 'checkout'])->name('customer.checkout');
    Route::post('/orders/{order}/pay', [CustomerWebController::class, 'processPayment'])->name('orders.process-payment');
    Route::delete('/orders/{order}', [CustomerWebController::class, 'cancel'])->name('orders.cancel');


    Route::post('/customer/chatbot-send', [CustomerWebController::class, 'chatbotSend'])->name('customer.chatbot.send');

    // ==========================================
    // 3. مسارات الإدارة (Admin Routes)
    // ==========================================
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminWebController::class, 'dashboard'])->name('admin.dashboard');

        // إدارة الأطعمة
        Route::post('/foods', [AdminWebController::class, 'storeFood'])->name('admin.foods.store');
        Route::put('/foods/{food}', [AdminWebController::class, 'updateFood'])->name('admin.foods.update');
        Route::delete('/foods/{food}', [AdminWebController::class, 'destroyFood'])->name('admin.foods.destroy');

        // إدارة المشروبات
        Route::post('/beverages', [AdminWebController::class, 'storeBeverage'])->name('admin.beverages.store');
        Route::delete('/beverages/{beverage}', [AdminWebController::class, 'destroyBeverage'])->name('admin.beverages.destroy');

        // إدارة الأقسام
        Route::post('/categories', [AdminWebController::class, 'storeCategory'])->name('admin.categories.store');
    });

});
Route::get('/customer/checkout/{id}', [CustomerWebController::class, 'checkout'])->name('customer.checkout');
Route::post('/customer/checkout/{id}', [CustomerWebController::class, 'processCheckout'])->name('customer.checkout.process');
