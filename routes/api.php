<?php

use App\Http\Controllers\FeatureController;
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
Route::get('/projects/{projectId}/features/{id}', [FeatureController::class,'show']);
Route::post('/projects/{projectId}/features', [FeatureController::class,'store']);
Route::patch('/projects/{projectId}/features/{id}', [FeatureController::class,'update']);
Route::delete('/projects/{projectId}/features/{id}', [FeatureController::class,'destroy']);

/*
                    STEP ROUTES
*/
