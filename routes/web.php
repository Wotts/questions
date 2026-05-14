<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::inertia('/', 'Course')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::get('/course', [CourseController::class, 'getCourseQuestions']);
Route::post('/course', [CourseController::class, 'submitCourseAnswers']);

require __DIR__.'/settings.php';
