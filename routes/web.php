<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SavedSearchController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceDetailController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WorkProgressController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\ModerasiKontenController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\VoucherController;

/*
|--------------------------------------------------------------------------
| SKENA – Web Routes
|--------------------------------------------------------------------------
*/

// ── Guest ──────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register'); // FR-01
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');                 // FR-02
    Route::post('/login', [LoginController::class, 'login']);
});

// ── Publik ─────────────────────────────────────────────────────────────────
Route::get('/', [SearchController::class, 'index'])->name('home');                                  // FR-25
Route::get('/services/{service}', [ServiceDetailController::class, 'show'])->name('services.show'); // FR-26
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Authenticated ──────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');             // FR-04
    Route::resource('profile', ProfileController::class)->only(['index', 'edit', 'update']);        // PBI-01
    Route::resource('experiences', ExperienceController::class);                                    // PBI-01
    Route::resource('skills', SkillController::class);                                              // PBI-02
    Route::resource('services', ServiceController::class)->except(['show']);                        // PBI-04
    Route::resource('portfolios', PortfolioController::class);                                      // PBI-06
    Route::resource('favorites', FavoriteController::class)->only(['index', 'store', 'destroy']);   // PBI-07
    Route::resource('saved-searches', SavedSearchController::class)->only(['index', 'store', 'destroy']); // PBI-08
    Route::resource('requests', ServiceRequestController::class);                                   // PBI-10
    Route::resource('orders', OrderController::class);                                              // PBI-11
    Route::resource('orders.negotiations', NegotiationController::class)->shallow();               // PBI-12
    Route::resource('payments', PaymentController::class)->only(['index', 'show', 'store']);       // PBI-13
    Route::resource('work-progress', WorkProgressController::class)->only(['index', 'show', 'update']); // PBI-14
    Route::resource('messages', MessageController::class)->only(['index', 'store']);               // PBI-15
    Route::resource('reviews', ReviewController::class)->only(['index', 'store']);                 // PBI-16
    Route::resource('notifications', NotificationController::class)->only(['index', 'update']);    // PBI-17
    Route::resource('reports', ReportController::class)->only(['index', 'create', 'store']);       // PBI-18
});

// ── Admin ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');       // FR-03
    Route::post('/users/{id}/verify', [UserController::class, 'verifyStudent'])->name('users.verify');
    Route::resource('users', UserController::class);                                               // FR-05
    Route::resource('roles', RoleController::class);                                               // FR-06
    Route::resource('logs', AdminLogController::class)->only(['index', 'show']);                   // PBI-03
    Route::resource('moderasi', ModerasiKontenController::class);                                  // PBI-03
    Route::resource('categories', ServiceCategoryController::class);                               // PBI-05
    Route::resource('vouchers', VoucherController::class);                                         // PBI-09
    Route::resource('reports', ReportController::class)->only(['index', 'update']);                // PBI-18
});
