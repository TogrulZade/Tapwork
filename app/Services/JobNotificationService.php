<?php

namespace App\Services;

use App\Models\JobPost;
use App\Models\TelegramUser;
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

    public function notifySubscribers(JobPost $job): void
    {
        $users = TelegramUser::all();

        foreach ($users as $user) {
            foreach ($user->keywords ?? [] as $keyword) {
                if (
                    stripos($job->title, $keyword) !== false ||
                    stripos($job->description, $keyword) !== false
                ) {
                    $this->sendMessage(
                        $user->chat_id,
                        "🔔 Yeni iş elanı:\n" .
                            "{$job->title}\n" .
                            "{$job->company->name}\n" .
                            "{$job->url}"
                    );
                    break; // hər istifadəçiyə yalnız bir dəfə göndər
                }
            }
        }
    }

    public function sendMessage(string $chatId, string $text): void
    {
        Http::post(env('TELEGRAM_URL'), [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
