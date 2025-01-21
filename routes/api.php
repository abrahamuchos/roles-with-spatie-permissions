<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login']);

    //Auth Routes
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('logout', [AuthController::class, 'logout']);
    });

});

