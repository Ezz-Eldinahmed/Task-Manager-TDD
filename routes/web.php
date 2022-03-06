<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectInvitationController;
use App\Http\Controllers\ProjectTasksController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth'])->group(function () {
    Route::resource('projects', ProjectController::class);

    Route::post('projects/{project}/tasks', [ProjectTasksController::class, 'store'])->name('tasks.store');

    Route::post('projects/{project}/invites', [ProjectInvitationController::class, 'store'])->name('project.invite');

    Route::put('tasks/{task}', [ProjectTasksController::class, 'update'])->name('tasks.update');
});

Route::view('/', 'welcome');

require __DIR__ . '/auth.php';
