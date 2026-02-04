<?php

use App\Http\Controllers\FeatureController;
use App\Http\Controllers\StepController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

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
                    AUTH ROUTES
*/
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/refresh', [AuthController::class,'refresh']);
Route::post('/me', [AuthController::class,'me']);