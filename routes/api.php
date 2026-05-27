<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\SwapRequestController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\WantController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/skills', [SkillController::class, 'index']);
Route::get('/skills/{skill}', [SkillController::class, 'show']);
Route::get('/users/{user}/profile', [UserProfileController::class, 'show']);
Route::get('/users/{user}/reviews', [ReviewController::class, 'userReviews']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/offers', [OfferController::class, 'index']);
    Route::post('/offers', [OfferController::class, 'store']);
    Route::put('/offers/{userOffer}', [OfferController::class, 'update']);
    Route::delete('/offers/{userOffer}', [OfferController::class, 'destroy']);

    Route::get('/wants', [WantController::class, 'index']);
    Route::post('/wants', [WantController::class, 'store']);
    Route::put('/wants/{userWant}', [WantController::class, 'update']);
    Route::delete('/wants/{userWant}', [WantController::class, 'destroy']);

    Route::get('/swaps', [SwapRequestController::class, 'index']);
    Route::post('/swaps', [SwapRequestController::class, 'store']);
    Route::patch('/swaps/{swapRequest}/accept', [SwapRequestController::class, 'accept']);
    Route::patch('/swaps/{swapRequest}/reject', [SwapRequestController::class, 'reject']);
    Route::patch('/swaps/{swapRequest}/complete', [SwapRequestController::class, 'complete']);
    Route::delete('/swaps/{swapRequest}', [SwapRequestController::class, 'destroy']);

    Route::post('/reviews', [ReviewController::class, 'store']);

    Route::get('/matches', [MatchController::class, 'perfect']);
    Route::get('/matches/partial', [MatchController::class, 'partial']);

    Route::middleware('admin')->group(function () {
        Route::post('/skills', [SkillController::class, 'store']);
        Route::put('/skills/{skill}', [SkillController::class, 'update']);
        Route::delete('/skills/{skill}', [SkillController::class, 'destroy']);
    });
});
