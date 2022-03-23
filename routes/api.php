<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;



//Public Routes
Route::post('/register/admin', [AdminController::class, 'register']);
Route::post('/login/admin', [AdminController::class, 'login']);

Route::post('/register/user', [UserController::class, 'register']);
Route::post('/login/user', [UserController::class, 'login']);



// Protected Routes for user
Route::middleware(['auth:user'])->group(function(){ 

Route::post('/logout/user', [UserController::class, 'logout']);

});



// Protected Routes for admin
Route::middleware(['auth:admin'])->group(function(){

Route::post('/posts', [PostController::class, 'store']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);
Route::post('/posts/delete-selected', [PostController::class, 'delete_selected_post']);
Route::post('/logout/admin', [AdminController::class, 'logout']);

});



// Protected Routes for user and admin
Route::middleware('operations_for_admin_and_user')->group(function(){

  Route::get('/posts', [PostController::class, 'index']);
  Route::get('/posts/{id}', [PostController::class, 'show']);
  Route::get('/posts/search/{city}', [PostController::class, 'search']);

});