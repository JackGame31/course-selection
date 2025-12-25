<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

// routes/api.php
Route::get('/course', [CourseController::class, 'get']);
Route::post('/course', [CourseController::class, 'store']);
Route::put('/course/{id}', [CourseController::class, 'update']);
Route::delete('/course/{id}', [CourseController::class, 'destroy']);