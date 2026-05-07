<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FornecedoresController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TypesController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/products', [ProductsController::class, 'index']);
    Route::post('/products/new', [ProductsController::class, 'store'])->name('products.store');

    Route::get('/products/new', [ProductsController::class, 'create'])->name('products.create');
    Route::get('/products/update/{id}', [ProductsController::class, 'edit']);
    Route::post('/products/update/', [ProductsController::class, 'update']);
    Route::get('/products/delete/{id}', [ProductsController::class, 'destroy']);

    Route::get('/types', [TypesController::class, 'index']);
    Route::get('/types/new', [TypesController::class, 'create'])->name('types.create');
    Route::post('/types/new', [TypesController::class, 'store'])->name('types.store');
    Route::get('/types/update/{id}', [TypesController::class, 'edit'])->name('types.edit');
    Route::post('/types/update/', [TypesController::class, 'update']);
    Route::get('/types/delete/{id}', [TypesController::class, 'destroy']);

    Route::get('/fornecedores', [FornecedoresController::class, 'index']);
    Route::get('/fornecedores/new', [FornecedoresController::class, 'create'])->name('fornecedores.create');
    Route::post('/fornecedores/new', [FornecedoresController::class, 'store'])->name('fornecedores.store');
    Route::get('/fornecedores/update/{id}', [FornecedoresController::class, 'edit'])->name('fornecedores.edit');
    Route::post('/fornecedores/update/', [FornecedoresController::class, 'update']);
    Route::get('/products/delete/{id}', [ProductsController::class, 'destroy']);

    Route::get('/types', [TypesController::class, 'index']);
Route::get('/types/new', [TypesController::class, 'create'])->name('types.create');
Route::post('/types/new', [TypesController::class, 'store'])->name('types.store');
Route::get('/types/update/{id}', [TypesController::class, 'edit'])->name('types.edit');
Route::post('/types/update', [TypesController::class, 'update'])->name('types.update');
Route::get('/types/delete/{id}', [TypesController::class, 'destroy']);

Route::get('/fornecedores', [FornecedoresController::class, 'index']);
Route::get('/fornecedores/new', [FornecedoresController::class, 'create'])->name('fornecedores.create');
Route::post('/fornecedores/new', [FornecedoresController::class, 'store'])->name('fornecedores.store');
Route::get('/fornecedores/update/{id}', [FornecedoresController::class, 'edit'])->name('fornecedores.edit');
Route::post('/fornecedores/update', [FornecedoresController::class, 'update'])->name('fornecedores.update');
Route::get('/fornecedores/delete/{id}', [FornecedoresController::class, 'destroy']);

});

require __DIR__.'/auth.php';
