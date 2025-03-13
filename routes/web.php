<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Task\TaskController;

Route::get('/', function () {
    return  redirect(auth()->check() ? route('tasks.index') : route('login'));
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('tasks', TaskController::class);

    Route::get('/projects', [App\Http\Controllers\Project\ProjectController::class, 'index'])
        ->name('projects.index');

    Route::get('/calendar', [App\Http\Controllers\Calendar\CalendarController::class, 'index'])
        ->name('calendar.index');

    Route::get('/reports', [App\Http\Controllers\Report\ReportController::class, 'index'])
        ->name('reports.index');

    Route::get('/team', [App\Http\Controllers\Team\TeamController::class, 'index'])
        ->name('team.index');

    Route::get('/tasks/categories', [App\Http\Controllers\Task\CategoryController::class, 'index'])
        ->name('tasks.categories.index');

    Route::get('/tasks/tags', [App\Http\Controllers\Task\TagController::class, 'index'])
        ->name('tasks.tags.index');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
