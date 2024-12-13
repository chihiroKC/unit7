<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProductsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/register', function () {
    return view('auth.register');
});
Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register'])->name('register');

Route::get('/products', [ProductsController::class, 'index'])->name('products.index')->middleware('auth');
Route::get('/products/add', [ProductsController::class, 'add'])->name('products.add');
Route::post('/products/store', [ProductsController::class, 'store'])->name('products.store');
Route::get('/products/{id}/info', [ProductsController::class, 'info'])->name('products.info');
Route::delete('/products/{id}/delete', [ProductsController::class, 'delete'])->name('products.delete');

Route::get('/products/{id}/edit', [ProductsController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductsController::class, 'update'])->name('products.update');
