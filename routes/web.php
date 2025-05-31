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

//Route::get('/', function () {
//    return view('welcome');
//})->name('home');

Route::view('/', 'home')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::redirect('settings', 'settings/profile');

        Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
        Volt::route('settings/password', 'settings.password')->name('settings.password');
        Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    });

Route::get('courses/showcase', [CourseController::class, 'showCase'])->name('courses.showcase');
Route::get('courses/{course}/curriculum', [CourseController::class, 'showCurriculum'])->name('courses.curriculum');


Route::delete('courses/{course}/image', [CourseController::class, 'destroyImage'])
    ->name('courses.image.destroy');
Route::resource('courses', CourseController::class);

Route::resource('departments', DepartmentController::class);

Route::resource('disciplines', DisciplineController::class);

Route::delete('teachers/{teacher}/photo', [TeacherController::class, 'destroyPhoto'])
    ->name('teachers.photo.destroy');
Route::resource('teachers', TeacherController::class);

Route::delete('students/{student}/photo', [StudentController::class, 'destroyPhoto'])
    ->name('students.photo.destroy');
Route::resource('students', StudentController::class);

Route::delete('administratives/{administrative}/photo', [AdministrativeController::class, 'destroyPhoto'])
    ->name('administratives.photo.destroy');
Route::resource('administratives', AdministrativeController::class);

// CART Related Routes
// Show the cart:
Route::get('cart', [CartController::class, 'show'])->name('cart.show');

// Add a discipline to the cart:
Route::post('cart/{discipline}', [CartController::class, 'addToCart'])->name('cart.add');

// Remove a discipline from the cart:
Route::delete('cart/{discipline}', [CartController::class, 'removeFromCart'])->name('cart.remove');


// Confirm (store) the cart and save disciplines registration on the database:
Route::post('cart', [CartController::class, 'confirm'])->name('cart.confirm');

// Clear the cart:
Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');


require __DIR__.'/auth.php';
