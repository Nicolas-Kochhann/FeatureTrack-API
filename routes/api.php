<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::middleware(['api', 'auth'])->group(function () {
    /*
                        PROJECT ROUTES
    */
    Route::get('/projects', [ProjectController::class,'list']);
    Route::post('/projects', [ProjectController::class,'store']);
    Route::get('/projects/{id}', [ProjectController::class,'show']);
    Route::patch('/projects/{id}', [ProjectController::class,'update']);
    Route::delete('/projects/{id}', [ProjectController::class,'destroy']);

    /*
                        FEATURE ROUTES
    */
    Route::get('/projects/{projectId}/features', [FeatureController::class,'list']);
    Route::get('/features/{id}', [FeatureController::class,'show']);
    Route::post('/projects/{projectId}/features', [FeatureController::class,'store']);
    Route::patch('/features/{id}', [FeatureController::class,'update']);
    Route::delete('/features/{id}', [FeatureController::class,'destroy']);

    /*
                        STEP ROUTES
    */
    Route::get('/features/{featureId}/steps', [StepController::class,'list']);
    Route::post('/features/{featureId}/steps', [StepController::class,'store']);
    Route::patch('/steps/{id}', [StepController::class,'update']);
    Route::delete('/steps/{id}', [StepController::class,'destroy']);

    /*
                        INVITE ROUTES
    */
    Route::get('/receivedInvites', [InviteController::class, 'listReceivedInvites']);
    Route::get('/sentInvites', [InviteController::class, 'listSentInvites']);  
    Route::get('/invites/{id}', [InviteController::class, 'show']);
    Route::post('/invites', [InviteController::class, 'store']);
    Route::patch('/invites/{id}', [InviteController::class, 'update']);
    Route::patch('/invites/{id}/respond', [InviteController::class, 'respondInvite']);
    Route::delete('/invites/{id}', [InviteController::class, 'destroy']);

    /*
                        AUTH ROUTES
    */
    Route::post('/auth/logout', [AuthController::class,'logout']);
    Route::post('/auth/refresh', [AuthController::class,'refresh']);
    Route::get('/auth/me', [AuthController::class,'me']);
});

Route::middleware(['api'])->group(function () {
    Route::post('/auth/register', [AuthController::class,'register']);
    Route::post('/auth/login', [AuthController::class,'login']);
});