<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\Auth\LoginController;


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

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register'); 

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/add', [ProductsController::class, 'add'])->name('products.add');
    Route::post('/products/store', [ProductsController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/info', [ProductsController::class, 'info'])->name('products.info');
    Route::delete('/products/{id}/delete', [ProductsController::class, 'delete'])->name('products.delete');
    Route::get('/products/{id}/edit', [ProductsController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductsController::class, 'update'])->name('products.update');
    Route::get('/products/search', [ProductsController::class, 'search'])->name('products.search');
});
