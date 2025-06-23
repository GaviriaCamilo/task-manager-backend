<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;


Route::get('/tasks/export-pdf', [TaskController::class, 'exportPdf']);
Route::apiResource('tasks', TaskController::class);