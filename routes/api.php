<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/users/store', [UserController::class , 'storeUser']);

Route::get('/users', [UserController::class, 'getUser']);