<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\QueueStatusController;
use App\Http\Controllers\Admin\StorageManagementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/project/{project}', [HomeController::class, 'getProject'])->name('project.show');
Route::get('/category/{slug}', [HomeController::class, 'categoryShow'])->name('category.show');
Route::get('/works', [HomeController::class, 'works'])->name('works.index');

// Like Routes
Route::post('/projects/{project}/like', [LikeController::class, 'toggleLike'])->name('projects.like');
Route::get('/projects/{project}/like-status', [LikeController::class, 'checkLike'])->name('projects.like.status');

// Download Route
Route::get('/projects/{project}/download', [HomeController::class, 'download'])->name('projects.download');

// User Profile Routes (Protected - Normal Users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'show'])->name('user.profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('user.profile.password.update');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Admin Login
Route::get('/admin-login', [AuthController::class, 'showLogin'])->name('admin.login');

// Queue Cron Route (Public with token authentication)
// This route can be called by cPanel cron jobs
// Usage: curl "https://yourdomain.com/queue/cron?token=YOUR_SECRET_TOKEN&max_jobs=5"
Route::get('/queue/cron', [StorageManagementController::class, 'runQueueCron'])->name('queue.cron');

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:root_admin,admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Slider Management
    Route::resource('sliders', \App\Http\Controllers\Admin\AdminSliderController::class);

    // Profile Management
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profile/email', [AdminProfileController::class, 'updateEmail'])->name('profile.email.update');

    Route::resource('skills', \App\Http\Controllers\Admin\SkillController::class)->except(['show', 'create']);

    // Creative Studio Content
    Route::get('/creative-studio', [\App\Http\Controllers\Admin\CreativeStudioController::class, 'edit'])->name('creative-studio.edit');
    Route::put('/creative-studio', [\App\Http\Controllers\Admin\CreativeStudioController::class, 'update'])->name('creative-studio.update');

    Route::get('/contact', [\App\Http\Controllers\Admin\ContactInfoController::class, 'edit'])->name('contact.edit');
    Route::put('/contact', [\App\Http\Controllers\Admin\ContactInfoController::class, 'update'])->name('contact.update');

    Route::get('/social-links', [\App\Http\Controllers\Admin\SocialLinkController::class, 'edit'])->name('social.edit');
    Route::put('/social-links', [\App\Http\Controllers\Admin\SocialLinkController::class, 'update'])->name('social.update');

    Route::get('/footer', [\App\Http\Controllers\Admin\FooterContentController::class, 'edit'])->name('footer.edit');
    Route::put('/footer', [\App\Http\Controllers\Admin\FooterContentController::class, 'update'])->name('footer.update');

    // Project Management
    Route::resource('projects', AdminProjectController::class);
    Route::get('/projects/{project}/queue-status', [QueueStatusController::class, 'checkProjectStatus'])->name('projects.queue-status');
    Route::get('/queue-status/{jobId}', [QueueStatusController::class, 'checkJobStatus'])->name('queue.status');

    // User Management
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::post('/categories/{category}/toggle-status', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    // Site Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('settings.update');

    // Site Optimization
    Route::get('/optimization', [\App\Http\Controllers\Admin\SiteOptimizationController::class, 'index'])->name('optimization.index');
    Route::post('/optimization/storage-link', [\App\Http\Controllers\Admin\SiteOptimizationController::class, 'createStorageLink'])->name('optimization.storage-link');
    Route::post('/optimization/migrate', [\App\Http\Controllers\Admin\SiteOptimizationController::class, 'runMigrations'])->name('optimization.migrate');
    Route::post('/optimization/clear', [\App\Http\Controllers\Admin\SiteOptimizationController::class, 'clearOptimization'])->name('optimization.clear');
    Route::post('/optimization/cache', [\App\Http\Controllers\Admin\SiteOptimizationController::class, 'cacheOptimization'])->name('optimization.cache');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Storage Management
    Route::get('/storage-management', [StorageManagementController::class, 'index'])->name('storage.index');
    Route::post('/storage-management/settings', [StorageManagementController::class, 'updateSettings'])->name('storage.settings.update');
    Route::post('/storage-management/test', [StorageManagementController::class, 'testConnection'])->name('storage.test');
    Route::post('/storage-management/toggle-avoid', [StorageManagementController::class, 'toggleAvoidS3'])->name('storage.toggle');
    Route::post('/storage-management/run-queue', [StorageManagementController::class, 'runQueue'])->name('storage.queue.run');
    Route::get('/storage-management/usage-logs', [StorageManagementController::class, 'usageLogs'])->name('storage.logs');
    Route::get('/storage-management/queue-logs', [StorageManagementController::class, 'queueLogs'])->name('storage.queue.logs');
    Route::post('/storage-management/logs/clear', [StorageManagementController::class, 'clearLogs'])->name('storage.logs.clear');

    // Queue Monitor
    Route::get('/queue-monitor', [\App\Http\Controllers\Admin\QueueMonitorController::class, 'index'])->name('queue.index');
    Route::get('/queue-monitor/stats', [\App\Http\Controllers\Admin\QueueMonitorController::class, 'stats'])->name('queue.stats');
});
