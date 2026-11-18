<?php

use App\Http\Controllers\Api\V1\AuthController as ApiAuthController;
use App\Http\Controllers\Api\V1\CategoryController as ApiCategoryController;
use App\Http\Controllers\Api\V1\GuideController as ApiGuideController;
use App\Http\Controllers\Api\V1\SchemaController as ApiSchemaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // ── Auth ────────────────────────────────────────────────────────────────
    // POST /api/v1/login  — get a Sanctum token (for Postman/Bruno testing)
    Route::post('/login', [ApiAuthController::class, 'login'])->name('api.v1.login');

    // POST /api/v1/logout — revoke token
    Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout'])->name('api.v1.logout');

    // ── Categories ──────────────────────────────────────────────────────────
    Route::get('/categories', [ApiCategoryController::class, 'index'])->name('api.v1.categories.index');

    // ── Guides ──────────────────────────────────────────────────────────────
    Route::get('/guides', [ApiGuideController::class, 'index'])->name('api.v1.guides.index');
    Route::get('/guides/{id}', [ApiGuideController::class, 'show'])->name('api.v1.guides.show');

    // Authenticated write actions (require Sanctum Bearer token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/guides', [ApiGuideController::class, 'store'])->name('api.v1.guides.store');
        Route::patch('/guides/{id}', [ApiGuideController::class, 'update'])->name('api.v1.guides.update');
        Route::delete('/guides/{id}', [ApiGuideController::class, 'destroy'])->name('api.v1.guides.destroy');
    });

    // ── Schema Validation demo ──────────────────────────────────────────────
    Route::post('/validate-guide-schema', [ApiSchemaController::class, 'validatePayload'])
        ->name('api.v1.schema.validate');
});

