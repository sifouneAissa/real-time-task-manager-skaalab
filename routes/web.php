<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('tasks/create', [\App\Http\Controllers\TaskController::class,'create'])->can('create task')->name('tasks.create');
Route::post('tasks', [\App\Http\Controllers\TaskController::class,'store'])->can('create task')->name('tasks.store');
Route::get('tasks/{task}/edit', [\App\Http\Controllers\TaskController::class,'edit'])->can('edit task')->name('tasks.edit');
Route::put('tasks/{task}', [\App\Http\Controllers\TaskController::class,'update'])->can('edit task')->name('tasks.update');
Route::put('tasks/status/{task}', [\App\Http\Controllers\TaskController::class,'updateStatus'])->name('tasks.updateStatus');
Route::delete('tasks/{task}', [\App\Http\Controllers\TaskController::class,'destroy'])->can('delete task')->name('tasks.delete');

require __DIR__.'/auth.php';
