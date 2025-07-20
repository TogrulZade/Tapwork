<?php

use App\Http\Controllers\Api\GetJobController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\TelegramBotController;
use App\Models\JobPost;
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

Route::group(['prefix' => 'telegram'], function () {
    Route::post("webhook", [TelegramBotController::class, "webbook"])->name("telegram.webhook");
});

Route::get('/post', function () {

    $jobs = JobPost::where("title", "like", "%PHP%")
        ->orWhere("description", "like", "%PHP%")
        ->with("company")
        ->get();


    $jobsArray = [];

    foreach ($jobs as $job) {
        $jobsArray[] = [
            'title' => $job->title,
            'description' => $job->description,
            'company' => $job->company->name,
            'url' => $job->url,
            'created_at' => $job->created_at,
        ];
    }

    $notify = new \App\Services\JobNotificationService();
    $notify->sendJobNotification($jobsArray);
})->name('post');
