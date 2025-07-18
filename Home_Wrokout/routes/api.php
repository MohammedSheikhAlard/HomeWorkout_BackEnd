<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PlanDayController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ExerciseLevelController;

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


Route::group([
    'prefix' => 'exercise'
], function () {

    Route::post('/addNewExercise', [ExerciseController::class, 'addNewExercise'])->middleware('auth:sanctum')->middleware('auth:sanctum');
    Route::post('/updateExercise', [ExerciseController::class, 'updateExercise'])->middleware('auth:sanctum');
    Route::get('/getAllExercises', [ExerciseController::class, 'getAllExercises'])->middleware('auth:sanctum');
    Route::delete('/deleteExercise', [ExerciseController::class, 'deleteExercise'])->middleware('auth:sanctum');
});
Route::group([
    'prefix' => 'wallet'
], function () {
    Route::get('/getbalance', [WalletController::class, 'getBalance'])->middleware('auth:sanctum');
    Route::post('/deposit', [WalletController::class, 'deposit'])->middleware('auth:sanctum');
    Route::post('/withdraw', [WalletController::class, 'withdraw'])->middleware('auth:sanctum');
    Route::get('/transactions', [WalletController::class, 'getTransactions'])->middleware('auth:sanctum');
    Route::post('/create', [WalletController::class, 'createWallet'])->middleware('auth:sanctum');
});


Route::group([
    'prefix' => 'exerciseLevel'
], function () {

    Route::post('/AddExerciseToLevel', [ExerciseLevelController::class, 'AddExerciseToLevel'])->middleware('auth:sanctum');
    Route::get('/getAllExerciseLevels', [ExerciseLevelController::class, 'getAllExerciseLevels']);
    Route::delete('/deleteExerciseLevel', [ExerciseLevelController::class, 'deleteExerciseLevel'])->middleware('auth:sanctum');
    Route::post('/updateExerciseLevel', [ExerciseLevelController::class, 'updateExerciseLevel'])->middleware('auth:sanctum');
    Route::get('/getAllExerciseLevelsByLevelId', [ExerciseLevelController::class, 'getAllExerciseLevelsByLevelId']);
});


Route::group([
    'prefix' => 'plan'
], function () {


    Route::post('addNewPlan', [PlanController::class, 'addNewPlan'])->middleware('auth:sanctum');
    Route::post('updatePlan', [PlanController::class, 'updatePlan'])->middleware('auth:sanctum');
    Route::delete('deletePlan', [PlanController::class, 'deletePlan'])->middleware('auth:sanctum');
    Route::post('restorePlan', [PlanController::class, 'restorePlan'])->middleware('auth:sanctum');
    Route::get('getPlan', [PlanController::class, 'getPlan']);
    Route::get('getAllPlans', [PlanController::class, 'getAllPlans']);
});


Route::group([
    'prefix' => 'planDay'
], function () {


    Route::post('addNewPlanDay', [PlanDayController::class, 'addNewPlanDay'])->middleware('auth:sanctum');
    Route::post('updatePlanDay', [PlanDayController::class, 'updatePlanDay'])->middleware('auth:sanctum');
    Route::get('getPlanDay', [PlanDayController::class, 'getPlanDay']);
    Route::get('getAllPlanDays', [PlanDayController::class, 'getAllPlanDays']);
});
