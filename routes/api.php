<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(BookController::class)->group(function () {
    Route::get('/book', 'index');
    Route::post('/book', 'store');
    Route::get('/book/{id}', 'show');
    Route::put('/book/{id}', 'update');
    Route::delete('/book/{id}', 'destroy');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'index');
    Route::post('/category', 'store');
    Route::get('/category/{id}', 'show');
    Route::put('/category/{id}', 'update');
    Route::delete('/category/{id}', 'destroy');
});

Route::controller(AuthorController::class)->group(function () {
    Route::get('/author', 'index');
    Route::get('/author/{id}', 'show');
    Route::post('/author', 'store');
    Route::put('/author/{id}', 'update');
    Route::delete('/author/{id}', 'destroy');
});
