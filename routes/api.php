<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;


Route::post('/users/store', [UserController::class , 'storeUser']);

Route::get('/users', [UserController::class, 'getUser']);