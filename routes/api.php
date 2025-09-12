<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/users', [UserController::class , 'storeUser']);

Route::get('/users', [UserController::class, 'getUser']);

Route::delete('/users/{user}', [UserController::class, 'destroy']);