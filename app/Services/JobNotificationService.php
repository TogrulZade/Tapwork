<?php

namespace App\Services;

use App\Models\JobPost;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Support\Facades\Http;

class JobNotificationService
{

    public function sendJobNotification(array $jobs)
    {
        foreach ($jobs as $job) {
            Http::post(env("TELEGRAM_URL"), [
                'chat_id' => '@tapworkaz',
                'text' => $job['title'] . "\n" .
                    "Tarix: " . Carbon::parse($job['start_date'])->format('d-m-Y') . "\n" .
                    "Şirkət: " . $job['company']->name . "\n" .
                    "Elan tarixi: " . Carbon::parse($job['created_at'])->format('d-m-Y H:i') . "\n" .
                    "Təsvir: " . $job['description'] . "\n" .
                    "Link: " . $job['url'] . "\n",
            ]);
        }
    }

    public function sendNewJobNotification(array $jobs)
    {
        foreach ($jobs as $job) {
            $exists = JobPost::where('url', $job['url'])->exists();
            if (!$exists) {
                Http::post(env("TELEGRAM_URL"), [
                    'chat_id' => env("TELEGRAM_CHAT_ID"),
                    'text' => $job['title'] . "\n" .
                        "Şirkət: " . $job['company'] . "\n" .
                        "Təsvir: " . $job['description'] . "\n" .
                        "Link: " . $job['url'] . "\n",
                ]);
            }
        }
    }
}
