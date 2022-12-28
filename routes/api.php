<?php

use App\Http\Controllers\Auth\AuthController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'getUser'])->middleware('auth:sanctum');


Route::prefix('book')->controller(BookController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::post('/update/{id}', 'update');
    Route::post('/delete/{id}', 'destroy');
});

Route::prefix('category')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::post('/update/{id}', 'update');
    Route::post('/delete/{id}', 'destroy');
});

Route::prefix('author')->controller(AuthorController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/show/{id}', 'show');
    Route::post('/store', 'store');
    Route::post('/update/{id}', 'update');
    Route::post('/delete/{id}', 'destroy');
});
