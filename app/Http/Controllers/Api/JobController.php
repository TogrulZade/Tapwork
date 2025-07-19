<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JobService;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }


    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = $this->jobService->search($query);


        foreach ($results as $job) {
            echo $job->company->name . "<br/>"; // Eager load company relationship
            echo $job->title . "<br/>";
            echo $job->description . "<br/>***<br/>";
        }
    }
}
