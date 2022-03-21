<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

// Route::get('/students', function(){
//     return 'All Student Info API';
// });

// Public Routes
Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{id}', [StudentController::class, 'show']);
Route::post('/students', [StudentController::class, 'store']);
Route::put('/students/{id}', [StudentController::class, 'update']);
Route::delete('/students/{id}', [StudentController::class, 'destroy']);
Route::get('/students/search/{city}', [StudentController::class, 'search']);

Route::post('/register/user', [UserController::class, 'register']);
Route::post('/login/user', [UserController::class, 'login']);

// Protected Routes
Route::middleware(['auth:user'])->group(function(){
Route::post('/logout/user', [UserController::class, 'logout']);
});


Route::post('/register/admin', [AdminController::class, 'register']);
Route::post('/login/admin', [AdminController::class, 'login']);

// Protected Routes
Route::middleware(['auth:admin'])->group(function(){
Route::post('/logout/admin', [AdminController::class, 'logout']);
});

// Route::resource('students', StudentController::class);


// Protected Routes
// Route::middleware('auth:sanctum')->get('/students', [StudentController::class, 'index']);
// Route::middleware('auth:sanctum')->get('/students/{id}', [StudentController::class, 'show']);

// Route::middleware(['auth:sanctum'])->group(function(){
//   Route::get('/students', [StudentController::class, 'index']);
//   Route::get('/students/{id}', [StudentController::class, 'show']);
//   Route::post('/students', [StudentController::class, 'store']);
//   Route::put('/students/{id}', [StudentController::class, 'update']);
//   Route::delete('/students/{id}', [StudentController::class, 'destroy']);
//   Route::get('/students/search/{city}', [StudentController::class, 'search']);
//   Route::post('/logout', [UserController::class, 'logout']);
// });


// Partially Protected
// // Public
// Route::get('/students', [StudentController::class, 'index']);
// Route::get('/students/{id}', [StudentController::class, 'show']);
// Route::get('/students/search/{city}', [StudentController::class, 'search']);

// // Protected
// Route::middleware(['auth:sanctum'])->group(function(){
//   Route::post('/students', [StudentController::class, 'store']);
//   Route::put('/students/{id}', [StudentController::class, 'update']);
//   Route::delete('/students/{id}', [StudentController::class, 'destroy']);
//   Route::post('/logout', [UserController::class, 'logout']);
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:user'])->get('/ok/user', function(){
    return 'Responses from user guard';
});

Route::middleware(['auth:admin'])->get('/ok/admin', function(){
    return 'Responses from admin guard';
});

Route::middleware(['operations_for_admin_and_user'])->get('operations-for-admin-and-user', function(){
    return 'operations for admin and user';
});