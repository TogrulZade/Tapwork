<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GetJobService;
use Illuminate\Http\Request;

class GetJobController extends Controller
{
    protected $getJobService;

    public function __construct(GetJobService $getJobService)
    {
        $this->getJobService = $getJobService;
    }

    public function __invoke(Request $request)
    {
        $jobs = $request->jobs;

        $data = $this->getJobService->getData($jobs);

        $this->getJobService->saveCompany($data);

        $this->getJobService->saveJob($data);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
