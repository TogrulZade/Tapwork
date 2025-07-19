<?php

namespace App\Services;

use App\Models\JobPost;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Http\JsonResponse;

class JobService
{

    public function search($query)
    {
        return JobPost::search($query)
            ->query(function ($builder) {
                $builder->with('company');
            })
            ->get();
    }
}
