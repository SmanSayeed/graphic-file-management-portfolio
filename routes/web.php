<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/project/{project}', [HomeController::class, 'getProject'])->name('project.show');

// Like Routes
Route::post('/projects/{project}/like', [LikeController::class, 'toggleLike'])->name('projects.like');
Route::get('/projects/{project}/like-status', [LikeController::class, 'checkLike'])->name('projects.like.status');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Admin Login
Route::get('/admin-login', [AuthController::class, 'showLogin'])->name('admin.login');

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:root_admin,admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/sliders', function () {
        return view('admin.sliders.index');
    })->name('sliders.index');

    Route::get('/sliders/create', function () {
        return view('admin.sliders.create');
    })->name('sliders.create');

    // Profile Management
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profile/email', [AdminProfileController::class, 'updateEmail'])->name('profile.email.update');

    Route::get('/skills', function () {
        return view('admin.skills.index');
    })->name('skills.index');

    Route::get('/contact', function () {
        return view('admin.contact.edit');
    })->name('contact.edit');

    Route::get('/social-links', function () {
        return view('admin.social.edit');
    })->name('social.edit');

    Route::get('/footer', function () {
        return view('admin.footer.edit');
    })->name('footer.edit');

    // Project Management
    Route::resource('projects', AdminProjectController::class);

    // User Management
    Route::resource('users', UserController::class);

    Route::get('/categories', function () {
        return view('admin.categories.index');
    })->name('categories.index');
});
