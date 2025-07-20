<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class JobNotificationService
{

    public function sendJobNotification(array $jobs)
    {
        foreach ($jobs as $job) {
            Http::post(env("TELEGRAM_URL"), [
                'chat_id' => 597076328,
                'text' => $job['title'] . "\n" .
                    "Elan tarixi: " . Carbon::parse($job['created_at'])->format('d-m-Y H:i') . "\n" .
                    $job['description'] . "\n" .
                    $job['company'] . "\n",
            ]);
        }
    }
}
