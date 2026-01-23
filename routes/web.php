<?php

use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/api/projects', [Project::class,'list']);
