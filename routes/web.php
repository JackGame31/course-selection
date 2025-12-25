<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
  return view('welcome');
});

Route::controller(AdminController::class)->group(function () {
  // Authentication related
  Route::group(['middleware' => 'guest:admin', 'guest'], function () {
    Route::get('/teacher/login', 'login')->name('teacher.login');
    Route::post('/teacher/login', 'authenticate')->name('teacher.authenticate');
    Route::get('/teacher/register', 'register')->name('teacher.register');
    Route::post('/teacher/register', 'store')->name('teacher.register.store');
  });
  Route::delete('/teacher/logout', 'logout')->name('teacher.logout')->middleware('auth:admin');

  // Calendar Related
  Route::middleware('auth:admin')->group(function () {
    Route::get('/teacher/calendar', 'calendar')->name('teacher.calendar');
  });
});

Route::controller(StudentController::class)->group(function () {
  // Authentication related
  Route::group(['middleware' => ['guest', 'guest:admin']], function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate')->name('login.authenticate');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'store')->name('register.store');
  });
  Route::delete('/student/logout', 'logout')->name('logout')->middleware('auth');

  // Calendar related
  Route::middleware('auth')->group(function () {
    Route::get('/student/calendar', 'calendar')->name('student.calendar');
    Route::post('/student/calendar', 'submitCourseSelection')->name('student.submitCourseSelection');
    Route::put('/student/calendar', 'resetCourseSelection')->name('student.resetCourseSelection');
  });
});
