<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportCommentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (Admin & User)
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reports - Accessible to both Admin & User (Role determines access level)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

    // Report Discussion / Comments
    Route::post('/reports/{report}/comments', [ReportCommentController::class, 'store'])->name('reports.comments.store');

    // In-app Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Profile Settings
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/settings', [ProfileController::class, 'update'])->name('profile.update');

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        // Report Actions
        Route::patch('/reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.update-status');
        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // Master Data Barang (Items)
        Route::resource('items', ItemController::class);

        // Master Kategori Barang (Categories)
        Route::resource('categories', CategoryController::class);

        // Manajemen Pengguna (Users)
        Route::resource('users', UserController::class);

        // Ekspor Laporan
        Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
        Route::get('/exports/pdf', [ExportController::class, 'pdf'])->name('exports.pdf');
        Route::get('/exports/excel', [ExportController::class, 'excel'])->name('exports.excel');
    });
});
