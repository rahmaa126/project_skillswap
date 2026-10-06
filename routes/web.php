<?php

use App\Http\Controllers\Admin\AiLogController;
use App\Http\Controllers\Admin\BattleController as AdminBattleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SkillCategoryController;
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Admin\SkillMatchController;
use App\Http\Controllers\Admin\SwapSessionController as AdminSwapSessionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Portal\BattleController as PortalBattleController;
use App\Http\Controllers\Portal\DashboardController as PortalDashboardController;
use App\Http\Controllers\Portal\MySkillController;
use App\Http\Controllers\Portal\ProfileController;
use App\Http\Controllers\Portal\ReviewController as PortalReviewController;
use App\Http\Controllers\Portal\SkillExploreController;
use App\Http\Controllers\Portal\SwapSessionController as PortalSwapSessionController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Shortcut /dashboard redirect
Route::get('/dashboard', function () {
    return redirect()->route('portal.dashboard');
})->middleware('auth');

// =========================================================================
// ROLE 1: PENGGUNA (USER) PORTAL - Protected with auth & role:user,admin
// =========================================================================
Route::middleware(['auth', 'role:user,admin'])->prefix('portal')->name('portal.')->group(function (): void {
    Route::get('/', [PortalDashboardController::class, 'index'])->name('dashboard');

    // Explore platform skills
    Route::get('skills', [SkillExploreController::class, 'index'])->name('skills.index');
    Route::get('skills/{skill}', [SkillExploreController::class, 'show'])->name('skills.show');

    // Manage user's offered & wanted skills
    Route::get('my-skills', [MySkillController::class, 'index'])->name('my-skills.index');
    Route::post('my-skills', [MySkillController::class, 'store'])->name('my-skills.store');
    Route::delete('my-skills/{userSkill}', [MySkillController::class, 'destroy'])->name('my-skills.destroy');

    // Peer-to-peer swap sessions
    Route::get('swaps', [PortalSwapSessionController::class, 'index'])->name('swaps.index');
    Route::post('swaps', [PortalSwapSessionController::class, 'store'])->name('swaps.store');
    Route::post('swaps/{swapSession}/respond', [PortalSwapSessionController::class, 'respond'])->name('swaps.respond');
    Route::patch('swaps/{swapSession}/complete', [PortalSwapSessionController::class, 'complete'])->name('swaps.complete');

    // Skill battles
    Route::get('battles', [PortalBattleController::class, 'index'])->name('battles.index');
    Route::post('battles', [PortalBattleController::class, 'store'])->name('battles.store');
    Route::get('battles/{battle}', [PortalBattleController::class, 'show'])->name('battles.show');
    Route::post('battles/{battle}/join', [PortalBattleController::class, 'join'])->name('battles.join');

    // Reviews
    Route::post('reviews', [PortalReviewController::class, 'store'])->name('reviews.store');

    // User profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

// =========================================================================
// ROLE 2: ADMINISTRATOR PANEL - Protected with auth & role:admin
// =========================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', AdminUserController::class)->except(['create', 'store']);

    // Categories
    Route::resource('categories', SkillCategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    // Skills
    Route::patch('skills/{skill}/toggle-status', [AdminSkillController::class, 'toggleStatus'])->name('skills.toggle-status');
    Route::resource('skills', AdminSkillController::class);

    // Swap Sessions
    Route::patch('swap-sessions/{swapSession}/status', [AdminSwapSessionController::class, 'updateStatus'])->name('swap-sessions.update-status');
    Route::resource('swap-sessions', AdminSwapSessionController::class)->only(['index', 'show', 'destroy']);

    // Battles
    Route::resource('battles', AdminBattleController::class)->only(['index', 'show', 'destroy']);

    // Skill Matches
    Route::patch('matches/{match}/status', [SkillMatchController::class, 'updateStatus'])->name('matches.update-status');
    Route::resource('matches', SkillMatchController::class)->only(['index', 'destroy']);

    // Reviews & Ratings
    Route::patch('reviews/{review}/toggle-hide', [AdminReviewController::class, 'toggleHide'])->name('reviews.toggle-hide');
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'destroy']);

    // Reports
    Route::patch('reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::resource('reports', ReportController::class)->only(['index', 'destroy']);

    // AI Engine Logs
    Route::get('ai-logs', [AiLogController::class, 'index'])->name('ai-logs.index');
});
