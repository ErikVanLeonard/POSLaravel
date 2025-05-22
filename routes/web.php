<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Rutas principales
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    
    // Rutas para proveedores
    Route::resource('providers', \App\Http\Controllers\ProviderController::class);
    Route::post('providers/{provider}/restore', [\App\Http\Controllers\ProviderController::class, 'restore'])->name('providers.restore');
    Route::delete('providers/{provider}/force-delete', [\App\Http\Controllers\ProviderController::class, 'forceDelete'])->name('providers.force-delete');
    Route::delete('providers/document/{document}', [\App\Http\Controllers\ProviderController::class, 'deleteDocument'])->name('providers.document.delete');
    Route::get('providers/document/{document}/download', [\App\Http\Controllers\ProviderController::class, 'downloadDocument'])->name('providers.document.download');
    
    // Rutas de auditoría
    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('index');
        Route::get('/{auditLog}', [\App\Http\Controllers\AuditLogController::class, 'show'])->name('show');
    });
});
