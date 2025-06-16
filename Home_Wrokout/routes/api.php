<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'user'
], function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group([
    'prefix' => 'admin'
], function () {

    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->middleware('auth:sanctum');
});


Route::group([
    'prefix' => 'category'
], function () {

    Route::post('/addNewCategory', [CategoryController::class, 'addNewCategory'])->middleware('auth:sanctum');
    Route::post('/updateCategory', [CategoryController::class, 'updateCategory'])->middleware('auth:sanctum');
    Route::get('/getAllCategory', [CategoryController::class, 'getAllCategory'])->middleware('auth:sanctum');
    Route::delete('/deleteCategory', [CategoryController::class, 'deleteCategory'])->middleware('auth:sanctum');
});
