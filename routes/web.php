<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HazardController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        // Sudah login → redirect berdasarkan role
        return redirect()->route(
            Auth::user()->role === 'admin' ? 'dashboard.admin' : 'dashboard.crew'
        );
    }

    // Belum login → ke login
    return view('auth.login');
})->name('home');

Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/forgot-password', [UserController::class, 'showForgot'])->name('forgot.show');
Route::post('/forgot-password', [UserController::class, 'forgot'])->name('forgot');


Route::middleware(['auth',RoleMiddleware::class . ':admin',])->group(function () {
  Route::get('/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    //user management
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

//task management
Route::get('/task', [TaskController::class, 'index'])->name('task.index');
Route::get('/task/create', [TaskController::class, 'create'])->name('task.create');
Route::post('/task', [TaskController::class, 'store'])->name('task.store');
Route::get('/task/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
Route::put('/task/{task}', [TaskController::class, 'update'])->name('task.update');
Route::delete('/task/{task}', [TaskController::class, 'destroy'])->name('task.destroy');

//hazard
Route::get('/hazard', [HazardController::class, 'index'])->name('hazard.index');

});

Route::middleware(['auth',RoleMiddleware::class . ':crew',])->group(function () {

   Route::get('/crew', [DashboardController::class, 'crew'])->name('dashboard.crew');

    //hazard

Route::get('/hazard/create', [HazardController::class, 'create'])->name('hazard.create');
Route::post('/hazard', [HazardController::class, 'store'])->name('hazard.store');
Route::get('/hazard/{hazard}/edit', [HazardController::class, 'edit'])->name('hazard.edit');
Route::put('/hazard/{hazard}', [HazardController::class, 'update'])->name('hazard.update');
Route::delete('/hazard/{hazard}', [HazardController::class, 'destroy'])->name('hazard.destroy');
Route::get('/hazard/my', [HazardController::class, 'myHazards'])->name('hazard.my');

//task
Route::get('/task/my', [TaskController::class, 'myTasks'])->name('task.my');
Route::patch('/task/{task}/cancel', [TaskController::class, 'cancel'])->name('task.cancel');
Route::patch('/task/{task}/start', [TaskController::class, 'start'])->name('task.start');
Route::patch('/task/{task}/finish', [TaskController::class, 'finish'])->name('task.finish');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});



    





