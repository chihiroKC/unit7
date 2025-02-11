<?php

use App\Http\Controllers\API\SalesController;
use Illuminate\Support\Facades\Route;

Route::post('/purchase', [SalesController::class, 'purchase']);
