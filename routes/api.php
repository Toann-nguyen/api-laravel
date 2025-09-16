<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;


Route::post('/users/store', [UserController::class , 'store']);

Route::get('/users', [UserController::class, 'index']);

Route::get('/post', [PostController::class, 'index']);

Route::post('/post', [PostController::class, 'store']);