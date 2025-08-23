<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BurnedCaloriesController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PlanDayController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ExerciseLevelController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PlanDayExerciseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPlanController;
use App\Http\Controllers\UserPlanProgressController;
use App\Models\BurnedCalories;
use App\Models\UserPlanProgress;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'user'
], function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::post('/editReminder', [UserController::class, 'editUserReminder'])->middleware('auth:sanctum');
    Route::get('/getReminder', [UserController::class, 'getUserReminder'])->middleware('auth:sanctum');

    Route::post('/editUserName', [UserController::class, 'editUserName'])->middleware('auth:sanctum');

    Route::post('/editPassword', [UserController::class, 'editPassword'])->middleware('auth:sanctum');

    Route::post('/editCaloriesGoal', [UserController::class, 'editCaloriesGoal'])->middleware('auth:sanctum');

    Route::post('/editBMI', [UserController::class, 'editBMI'])->middleware('auth:sanctum');

    Route::post('/updateLevel', [UserController::class, 'updateLevel'])->middleware('auth:sanctum');

    Route::get('/getActivityData', [UserController::class, 'getActivityData'])->middleware('auth:sanctum');

    Route::post('/updateTargetCalories', [UserController::class, 'updateTargetCalories'])->middleware('auth:sanctum');

    Route::post('/editBirthDate', [UserController::class, 'editBirthDate'])->middleware('auth:sanctum');

    Route::get('/getUserAge', [UserController::class, 'getUserAge'])->middleware('auth:sanctum');

    Route::post('/updateGender', [UserController::class, 'updateGender'])->middleware('auth:sanctum');

    Route::get('/getUserInfo', [UserController::class, 'getUserInfo'])->middleware('auth:sanctum');

    Route::post('/resetPassword', [UserController::class, 'resetPassword']);
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
    Route::get('/getAllUserCategory', [CategoryController::class, 'getAllUserCategory'])->middleware('auth:sanctum');
});

Route::group([
    'prefix' => 'level'
], function () {

    Route::get('/getAllLevel', [LevelController::class, 'getAllLevels'])->middleware('auth:sanctum');
    Route::get('/getLevelsByCategoryID', [LevelController::class, 'getLevelsByCategoryID'])->middleware('auth:sanctum');
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
    Route::get('/checkUserHaveWallet', [WalletController::class, 'checkUserHaveWallet'])->middleware('auth:sanctum');
});


Route::group([
    'prefix' => 'exerciseLevel'
], function () {

    Route::post('/AddExerciseToLevel', [ExerciseLevelController::class, 'AddExerciseToLevel'])->middleware('auth:sanctum');
    Route::get('/getAllExerciseLevels', [ExerciseLevelController::class, 'getAllExerciseLevels']);
    Route::delete('/deleteExerciseLevel', [ExerciseLevelController::class, 'deleteExerciseLevel'])->middleware('auth:sanctum');
    Route::post('/updateExerciseLevel', [ExerciseLevelController::class, 'updateExerciseLevel'])->middleware('auth:sanctum');
    Route::get('/getAllExerciseLevelsByLevelId', [ExerciseLevelController::class, 'getAllExerciseLevelsByLevelId']);
    Route::get('/getExerciseLevelsByExerciesLevelId', [ExerciseLevelController::class, 'getExerciseLevelsByExerciesLevelId'])->middleware('auth:sanctum');
    Route::get('/getExerciseLevelsByExerciesLevelIdandCategoryId', [ExerciseLevelController::class, 'getExerciseLevelsByExerciesLevelIdandCategoryId'])->middleware('auth:sanctum');
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
    Route::get('/getPlansByUserLevelID', [PlanController::class, 'getPlansByUserLevelID'])->middleware('auth:sanctum');
});


Route::group([
    'prefix' => 'planDay'
], function () {


    Route::post('addNewPlanDay', [PlanDayController::class, 'addNewPlanDay'])->middleware('auth:sanctum');
    Route::post('updatePlanDay', [PlanDayController::class, 'updatePlanDay'])->middleware('auth:sanctum');
    Route::get('getPlanDay', [PlanDayController::class, 'getPlanDay']);
    Route::get('getAllPlanDays', [PlanDayController::class, 'getAllPlanDays']);
    Route::get('getAllUserPlanDays', [PlanDayController::class, 'getAllUserPlanDays'])->middleware('auth:sanctum');
});


Route::group([
    'prefix' => 'planDayExercise'
], function () {


    Route::post('addNewPlanDayExercise', [PlanDayExerciseController::class, 'addNewPlanDayExercise'])->middleware('auth:sanctum');
    Route::post('updatePlanDayExercise', [PlanDayExerciseController::class, 'updatePlanDayExercise'])->middleware('auth:sanctum');
    Route::get('getPlanDayExercise', [PlanDayExerciseController::class, 'getPlanDayExercise']);
    Route::get('getAllPlanDayExercises', [PlanDayExerciseController::class, 'getAllPlanDayExercises']);
    Route::get('getAllUserPlanDayExercises', [PlanDayExerciseController::class, 'getAllUserPlanDayExercises'])->middleware('auth:sanctum');;
});
Route::group([
    'prefix' => 'userPlan'

], function () {

    Route::post('/linkPlanToUser', [UserPlanController::class, 'LinkPlanToUser'])->middleware('auth:sanctum');
    Route::post('/switch To Next Plan', [UserPlanController::class, 'switchToNextPlan'])->middleware('auth:sanctum');
    Route::delete('/deleteCurrentUserPlan', [UserPlanController::class, 'deleteCurrentUserPlan'])->middleware('auth:sanctum');
    Route::get('/getUserCurrentPlan', [UserPlanController::class, 'getUserCurrentPlan'])->middleware('auth:sanctum');
});


Route::group([
    'prefix' => 'burnedCalorie'

], function () {

    Route::post('/addExerciseCaloriesToday', [BurnedCaloriesController::class, 'addExerciseCaloriesToday'])->middleware('auth:sanctum');
    Route::post('/switch', [UserPlanController::class, 'switchToNextPlan'])->middleware('auth:sanctum');
    Route::delete('/delete', [UserPlanController::class, 'deletePlan'])->middleware('auth:sanctum');
});

Route::group([
    'prefix' => 'userPlanProgress'

], function () {

    Route::post('/saveUserDailyProgress', [UserPlanProgressController::class, 'saveUserDailyProgress'])->middleware('auth:sanctum');
});
