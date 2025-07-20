<?php

use App\Http\Controllers\Api\GetJobController;
use App\Http\Controllers\Api\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'bulk'], function () {
    Route::post('/', GetJobController::class);
});

Route::group(['prefix' => 'jobs'], function () {
    Route::get('/', [JobController::class, "search"]);
});

Route::get('/post', function () {
    Http::post("https://api.telegram.org/bot7375579995:AAGnzRWGWAVwrjCD150C9AXf2Xkqi57jo-M/sendMessage", [
        'chat_id' => 597076328,
        'text' => 'Yeni iş elanı gəldi!',
    ]);
})->name('post');
