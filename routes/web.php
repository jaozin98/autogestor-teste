<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do AutoGestor
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Produtos - Resource Routes
    Route::resource('products', ProductController::class)->middleware('block.admin.crud');
    Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status')->middleware('block.admin.crud');

    // Categorias - Resource Routes
    Route::resource('categories', CategoryController::class)->middleware('block.admin.crud');
    Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status')->middleware('block.admin.crud');

    // Marcas - Resource Routes
    Route::resource('brands', BrandController::class)->middleware('block.admin.crud');
    Route::patch('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status')->middleware('block.admin.crud');

    // Usuários - Resource Routes (apenas para administradores)
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/bulk-update', [UserController::class, 'bulkUpdate'])->name('users.bulk-update');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Rotas de teste para middlewares
Route::get('/test-route', function () {
    return response('Acesso permitido', 200);
})->middleware('check.permission:test-permission');

Route::get('/test-route-multiple', function () {
    return response('Acesso permitido', 200);
})->middleware('check.permission:permission-1,permission-2');

require __DIR__ . '/auth.php';
