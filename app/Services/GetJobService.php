<?php

namespace App\Services;

use App\Models\Company;
use App\Models\JobPost;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Support\Facades\Log;

class GetJobService
{
    public function getData(array $jobs): array
    {
        $data = [];

        foreach ($jobs as $job) {
            $data[] = [
                'title' => $job['title'],
                'url' => $job['url'],
                'description' => $job['description'],
                'company' => $job['company'],
                'logo' => $job['logo'] ?? null,
                'start_date' => Carbon::parse(parseAzeriDate($job['startDate']))->toDateString(),
                'end_date' => Carbon::parse(parseAzeriDate($job['endDate']))->toDateString(),
            ];
        }

        return $data;
    }

    public function saveJob(array $data)
    {
        foreach ($data as $job) {
            $company = Company::where('name', $job['company'])->first();
            if (!$company) {
                continue; // Skip if company not found
            }

            $existingJob = JobPost::where('url', $job['url'])->first();

            if ($existingJob) {
                // Lazım gələrsə update elə
                $existingJob->update($data);
                Log::channel('job')->info('Job already exists, updated: ' . $job['title']);
                return false; // yeni elan deyil
            } else {
                $company->jobs()->create($data);
                Log::channel('job')->info('New job created: ' . $job['title']);
                return true; // yeni elan yaradıldı
            }

            // JobPost::updateOrCreate(
            //     ['url' => $job['url']],
            //     [
            //         'title' => $job['title'],
            //         'description' => $job['description'],
            //         "company_id" => $company->id,
            //         'start_date' => $job['start_date'],
            //         'end_date' => $job['end_date'],
            //     ]
            // );
        }
    }

    public function saveCompany(array $data): void
    {
        try {
            foreach ($data as $job) {
                Company::updateOrCreate(
                    ['name' => trim($job['company'])],
                    [
                        'logo' => $job['logo'] ?? null,
                    ]
                );
            }
        } catch (\Exception $e) {
            // Handle exception if needed, e.g., log the error
            Log::error('Error saving company data: ' . $e->getMessage());
        }
    }
}
