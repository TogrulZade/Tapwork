<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GetJobService;
use App\Services\JobNotificationService;
use Illuminate\Http\Request;

class GetJobController extends Controller
{
    protected $getJobService;
    protected $jobNotificationService;

    public function __construct(GetJobService $getJobService, JobNotificationService $jobNotificationService)
    {
        $this->getJobService = $getJobService;
        $this->jobNotificationService = $jobNotificationService;
    }

    public function __invoke(Request $request)
    {
        $jobs = $request->jobs;

        $data = $this->getJobService->getData($jobs); // datanı düzəlt

        $this->getJobService->saveCompany($data); // şirkətləri save et

        $newJobs = $this->getJobService->saveJob($data); // yalnız yeni yaranan jobları topla

        if (!empty($newJobs)) {
            $this->jobNotificationService->sendNewJobNotification($newJobs); // onlara notifikasiya göndər
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'new_job_count' => count($newJobs),
        ]);
    }
}
