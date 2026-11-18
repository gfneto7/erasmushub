<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\GuideController as AdminGuideController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/guides', [GuideController::class, 'index'])->name('guides.index');
Route::get('/guides/{slug}', [GuideController::class, 'show'])->name('guides.show');
Route::get('/about', AboutController::class)->name('about');

// Language Switcher (EN, PT, HR)
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

/*
|--------------------------------------------------------------------------
| Authenticated User Profile & Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->canManageGuides()) {
            return redirect()->route('admin.guides.index');
        }

        return redirect()->route('guides.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| CMS & Administration (Protected by Auth & Policies)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Guides Management (Editors & Admins)
    Route::resource('guides', AdminGuideController::class);

    // Categories Management (Admins)
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // User Management (Admins)
    Route::controller(AdminUserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::patch('/{user}', 'update')->name('update');
        Route::patch('/{user}/toggle-active', 'toggleActive')->name('toggle-active');
    });
});

require __DIR__.'/auth.php';
