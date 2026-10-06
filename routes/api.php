<?php

use App\Http\Controllers\Api\V1\AiLogController;
use App\Http\Controllers\Api\V1\BattleController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\SkillCategoryController;
use App\Http\Controllers\Api\V1\SkillController;
use App\Http\Controllers\Api\V1\SkillMatchController;
use App\Http\Controllers\Api\V1\SwapSessionController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    // Users & profiles
    Route::get('users/{user}/skills', [UserController::class, 'skills'])->name('users.skills');
    Route::apiResource('users', UserController::class);

    // Skill Categories
    Route::apiResource('categories', SkillCategoryController::class);

    // Skills
    Route::apiResource('skills', SkillController::class);

    // Swap Sessions
    Route::apiResource('swap-sessions', SwapSessionController::class);

    // Battles
    Route::apiResource('battles', BattleController::class)->only(['index', 'store', 'show', 'update']);

    // Matches
    Route::apiResource('matches', SkillMatchController::class)->only(['index', 'store', 'show', 'update']);

    // Reviews
    Route::apiResource('reviews', ReviewController::class)->only(['index', 'store', 'show', 'destroy']);

    // Reports
    Route::apiResource('reports', ReportController::class)->only(['index', 'store', 'show', 'update']);

    // AI Engine Logs
    Route::apiResource('ai-logs', AiLogController::class)->only(['index', 'store', 'show']);
});
