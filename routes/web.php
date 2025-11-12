<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HazardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/master', function () {
    return view('master');
});

Route::get('/admin', function () {
        return view('dashboard.admin');
    })->name('dashboard.admin');

    Route::get('/crew', function () {
        return view('dashboard.crew');
    })->name('dashboard.crew');

Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

Route::get('/task', [TaskController::class, 'index'])->name('task.index');
Route::get('/task/create', [TaskController::class, 'create'])->name('task.create');
Route::post('/task', [TaskController::class, 'store'])->name('task.store');
Route::get('/task/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
Route::put('/task/{task}', [TaskController::class, 'update'])->name('task.update');
Route::delete('/task/{task}', [TaskController::class, 'destroy'])->name('task.destroy');

Route::get('/hazard', [HazardController::class, 'index'])->name('hazard.index');
Route::get('/hazard/create', [HazardController::class, 'create'])->name('hazard.create');
Route::post('/hazard', [HazardController::class, 'store'])->name('hazard.store');
Route::get('/hazard/{hazard}/edit', [HazardController::class, 'edit'])->name('hazard.edit');
Route::put('/hazard/{hazard}', [HazardController::class, 'update'])->name('hazard.update');
Route::delete('/hazard/{hazard}', [HazardController::class, 'destroy'])->name('hazard.destroy');