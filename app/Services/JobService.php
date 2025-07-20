<?php

namespace App\Services;

use App\Models\JobPost;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Http\JsonResponse;

class JobService
{

    public function search($query)
    {
        // return JobPost::search($query)
        //     ->query(function ($builder) {
        //         $builder->with('company');
        //     })
        //     ->get();

        return JobPost::where('title', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->with('company')
            ->get();
    }

    public function isJobSent($jobUrl): bool
    {
        return JobPost::where('url', $jobUrl)->whereSent(true)->exists();
    }
}
