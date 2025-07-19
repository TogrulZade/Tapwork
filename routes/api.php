<?php

use App\Http\Controllers\Api\GetJobController;
use App\Http\Controllers\Api\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'bulk'], function () {
    Route::post('/', GetJobController::class);
});

Route::group(['prefix' => 'jobs'], function () {
    Route::get('/', [JobController::class, "search"]);
});
