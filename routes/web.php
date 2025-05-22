<?php

use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CartController;
use App\Models\Discipline;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;
use App\Http\Controllers\CategoryController;

/* ----- PUBLIC ROUTES ----- */

Route::view('/', 'home')->name('home');
Route::get('courses/showcase', [CourseController::class, 'showCase'])->name('courses.showcase')
    ->can('viewShowCase', Course::class);
Route::get('courses/{course}/curriculum', [CourseController::class, 'showCurriculum'])->name('courses.curriculum')
    ->can('viewCurriculum', Course::class);

/* ----- Non-Verified users ----- */
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

/* ----- Verified users ----- */
Route::middleware('auth', 'verified')->group(function () {
    Route::delete('courses/{course}/image', [CourseController::class, 'destroyImage'])
        ->name('courses.image.destroy')
        ->can('update', Course::class);

    Route::get('disciplines/my', [DisciplineController::class, 'myDisciplines'])
        ->name('disciplines.my')
        ->can('viewMy', Discipline::class);
    Route::resource('disciplines', DisciplineController::class)->except(['index', 'show']);

    // Teacher routes
    Route::get('teachers/my', [TeacherController::class, 'myTeachers'])
        ->name('teachers.my')
        ->can('viewMy', Teacher::class);
    Route::delete('teachers/{teacher}/photo', [TeacherController::class, 'destroyPhoto'])
        ->name('teachers.photo.destroy')
        ->can('update', 'teacher');
    Route::resource('teachers', TeacherController::class);

    // Student routes
    Route::get('students/my', [StudentController::class, 'myStudents'])
        ->name('students.my')
        ->can('viewMy', Student::class);
    Route::delete('students/{student}/photo', [StudentController::class, 'destroyPhoto'])
        ->name('students.photo.destroy')
        ->can('update', 'student');
    Route::resource('students', StudentController::class);

    Route::delete('administratives/{administrative}/photo', [AdministrativeController::class, 'destroyPhoto'])
        ->name('administratives.photo.destroy')
        ->can('update', 'administrative');
    Route::resource('administratives', AdministrativeController::class);

    //Category routes
    Route::get('')

    //Course resource routes are protected by CoursePolicy on the controller
    // The route 'show' is public (for anonymous user)
    Route::resource('courses', CourseController::class)->except(['show']);
    //Department resource routes are protected by DepartmentPolicy on the controller
    Route::resource('departments', DepartmentController::class);

    // Dashboard - only for admin Admin routes
    Route::view('dashboard', 'dashboard')->name('dashboard')->can('admin');

    // CART Related Routes
    Route::post('cart', [CartController::class, 'confirm'])->name('cart.confirm')
        ->can('confirm-cart');
});

/* ----- OTHER PUBLIC ROUTES ----- */

// CART Related Routes
Route::middleware('can:use-cart')->group(function () {
    Route::get('cart', [CartController::class, 'show'])->name('cart.show');
    Route::post('cart/{discipline}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('cart/{discipline}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');
});

/* these routes should be positioned after related routes to avoid conflicts */
//Course show is public.
Route::resource('courses', CourseController::class)->only(['show']);
Route::resource('disciplines', DisciplineController::class)->only(['index', 'show']);


require __DIR__.'/auth.php';
