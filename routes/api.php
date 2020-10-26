<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// User Controller
Route::post("register", [UserController::class, "registerUser"]);

Route::post("login", [UserController::class, "loginUser"]);

Route::middleware('auth:api')->group( function () {

    Route::get("user", [UserController::class, "userDetail"]);

    Route::resource('posts', PostController::class);
});


