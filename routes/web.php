<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tasks', TaskController::class)->except(['show', 'create']);
    Route::get('/tasks/datatable', [TaskController::class, 'datatable'])->name('tasks.datatable');
    Route::get('/tasks/users', [TaskController::class, 'listUsers'])->name('tasks.users');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'markAsCompleted'])->name('tasks.complete');

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['show', 'create']);
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::get('users/{user}/can-delete', [UserController::class, 'canDelete']);
});



require __DIR__ . '/auth.php';
