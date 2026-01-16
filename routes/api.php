<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn(Request $request) => $request->user());
    //News Routes
    Route::post('/news', [App\Http\Controllers\NewsController::class, 'store']);
    Route::delete('/news/{news}', [App\Http\Controllers\NewsController::class, 'destory']);
    //User Routes
    Route::get('/user/news', [UserController::class, 'newsByUser']);
    Route::post('/user/logout', [UserController::class, 'logout']);
    Route::post('/user/update-profile', [UserController::class, 'updateProfile']);
    //comment
    Route::post('/comment', [CommentController::class, 'store']);
});
Route::get('/news', [NewsController::class, 'index']);

//News Routes
Route::get('/news/{news}', [App\Http\Controllers\NewsController::class, 'show']);

//Category Routes
Route::get('/category', [CategoryController::class, 'index']);
Route::post('/category', [CategoryController::class, 'store']);

//User Routes
Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);
