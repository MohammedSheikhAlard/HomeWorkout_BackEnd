<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanDayController;
use App\Http\Controllers\PlanDayExerciseController;
use App\Http\Controllers\ExerciseLevelController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::get('/login', [AdminController::class, 'loginPage'])->name('login');
    Route::post('/login', [AdminController::class, 'webLogin'])->name('login.submit');
    Route::post('/logout', [AdminController::class, 'webLogout'])->name('logout');

    Route::get('/dashboard', [AdminController::class, 'webDashboard'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'usersPage'])->name('users');
    Route::post('/users/{user}/delete', [AdminController::class, 'userDelete'])->name('users.delete');
    Route::post('/users/{user}/wallet/create', [AdminController::class, 'userWalletCreate'])->name('users.wallet.create');
    Route::post('/users/{user}/wallet/update', [AdminController::class, 'userWalletUpdate'])->name('users.wallet.update');

    Route::get('/exercises', [AdminController::class, 'exercisesPage'])->name('exercises');
    Route::post('/exercises', [AdminController::class, 'exerciseStore'])->name('exercises.store');
    Route::post('/exercises/{exercise}/update', [AdminController::class, 'exerciseUpdate'])->name('exercises.update');
    Route::post('/exercises/{exercise}/delete', [AdminController::class, 'exerciseDelete'])->name('exercises.delete');
    Route::get('/exercises/trashed', [AdminController::class, 'exercisesTrashedPage'])->name('exercises.trashed');
    Route::post('/exercises/{id}/restore', [AdminController::class, 'exerciseRestore'])->name('exercises.restore');
    Route::post('/exercises/{id}/force-delete', [AdminController::class, 'exerciseForceDelete'])->name('exercises.force-delete');

    Route::get('/categories', [CategoryController::class, 'webCategoriesPage'])->name('categories');
    Route::post('/categories', [CategoryController::class, 'webCategoryStore'])->name('categories.store');
    Route::post('/categories/{category}/update', [CategoryController::class, 'webCategoryUpdate'])->name('categories.update');
    Route::post('/categories/{category}/delete', [CategoryController::class, 'webCategoryDelete'])->name('categories.delete');
    Route::get('/categories/trashed', [AdminController::class, 'categoriesTrashedPage'])->name('categories.trashed');
    Route::post('/categories/{id}/restore', [AdminController::class, 'categoryRestore'])->name('categories.restore');
    Route::post('/categories/{id}/force-delete', [AdminController::class, 'categoryForceDelete'])->name('categories.force-delete');

    Route::get('/exercise-levels', [ExerciseLevelController::class, 'webExerciseLevelsPage'])->name('exercise-levels');
    Route::post('/exercise-levels', [ExerciseLevelController::class, 'webExerciseLevelStore'])->name('exercise-levels.store');
    Route::post('/exercise-levels/{exerciseLevel}/update', [ExerciseLevelController::class, 'webExerciseLevelUpdate'])->name('exercise-levels.update');
    Route::post('/exercise-levels/{exerciseLevel}/delete', [ExerciseLevelController::class, 'webExerciseLevelDelete'])->name('exercise-levels.delete');
    Route::get('/exercise-levels/trashed', [ExerciseLevelController::class, 'webExerciseLevelsTrashedPage'])->name('exercise-levels.trashed');
    Route::post('/exercise-levels/{id}/restore', [ExerciseLevelController::class, 'webExerciseLevelRestore'])->name('exercise-levels.restore');
    Route::post('/exercise-levels/{id}/force-delete', [ExerciseLevelController::class, 'webExerciseLevelForceDelete'])->name('exercise-levels.force-delete');

    Route::get('/plans', [PlanController::class, 'webPlansPage'])->name('plans');
    Route::post('/plans', [PlanController::class, 'webPlanStore'])->name('plans.store');
    Route::post('/plans/{plan}/update', [PlanController::class, 'webPlanUpdate'])->name('plans.update');
    Route::post('/plans/{plan}/delete', [PlanController::class, 'webPlanDelete'])->name('plans.delete');
    Route::get('/plans/trashed', [PlanController::class, 'webPlansTrashedPage'])->name('plans.trashed');
    Route::post('/plans/{id}/restore', [PlanController::class, 'webPlanRestore'])->name('plans.restore');
    Route::post('/plans/{id}/force-delete', [PlanController::class, 'webPlanForceDelete'])->name('plans.force-delete');

    Route::get('/plans/{plan}/days', [PlanDayController::class, 'webPlanDaysPage'])->name('plans.days');
    Route::post('/plans/{plan}/days', [PlanDayController::class, 'webPlanDayStore'])->name('plans.days.store');
    Route::post('/plans/days/{planDay}/update', [PlanDayController::class, 'webPlanDayUpdate'])->name('plans.days.update');
    Route::post('/plans/days/{planDay}/delete', [PlanDayController::class, 'webPlanDayDelete'])->name('plans.days.delete');
    Route::post('/plans/days/{planDay}/toggle-rest', [PlanDayController::class, 'webToggleRestDay'])->name('plans.days.toggle-rest');

    Route::get('/plans/days/{planDay}/exercises', [PlanDayExerciseController::class, 'webDayExercisesPage'])->name('plans.day.exercises');
    Route::post('/plans/days/{planDay}/exercises', [PlanDayExerciseController::class, 'webAddExerciseToDay'])->name('plans.day.exercises.store');
    Route::post('/plans/days/exercises/{planDayExercise}/update', [PlanDayExerciseController::class, 'webUpdateExerciseInDay'])->name('plans.day.exercises.update');
    Route::post('/plans/days/exercises/{planDayExercise}/delete', [PlanDayExerciseController::class, 'webDeleteExerciseFromDay'])->name('plans.day.exercises.delete');
});

require __DIR__ . '/auth.php';
