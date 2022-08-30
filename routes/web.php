<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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


Route::get('/', [FileController::class, 'index'])->name('file.index');

Route::get('/file-show', [FileController::class, 'showFile'])->name('file.show');
Route::get('/file-convert', [FileController::class, 'convert'])->name('file.convert');
Route::post('/order-store', [FileController::class, 'store'])->name('order.store');

Route::get('/previous-orders', [FileController::class, 'getPreviousOrder'])->name('order.previous');
Route::get('/toppings-used', [FileController::class, 'toppingsUsed'])->name('used.toppings');