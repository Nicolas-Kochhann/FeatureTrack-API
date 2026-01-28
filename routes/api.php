<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::get('/projects', [ProjectController::class,'list']);
Route::post('/projects', [ProjectController::class,'store']);
Route::get('/projects/{id}', [ProjectController::class,'show']);
Route::patch('/projects/{project}', [ProjectController::class,'update']);
Route::delete('/projects/{project}', [ProjectController::class,'destroy']);

