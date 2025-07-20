<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramBotController extends Controller
{
    public function webbook(Request $request)
    {
        $message = $request->input('message');

        if (!$message || !isset($message['text'], $message['chat']['id'])) {
            return response()->noContent(); // Səssiz cavab
        }

        $chatId = $message['chat']['id'];
        $text = trim(strtolower($message['text']));

        // İstifadəçini tap və ya yarat
        $user = TelegramUser::firstOrCreate(['chat_id' => $chatId], [
            'username' => $message['from']['username'] ?? null,
        ]);

        // Komandaları emal et
        switch ($text) {
            case '/start':
                $this->sendMessage($chatId, "Salam, *{$user->username}*! 👋\n\nZəhmət olmasa maraqlandığın açar sözləri vergüllə ayıraraq yaz:\n\n*Məsələn:* `PHP, Laravel, DevOps`");
                return;

            case '/my_keywords':
                $keywords = $user->keywords ? implode(', ', json_decode($user->keywords)) : 'Hələ açar söz yox.';
                $this->sendMessage($chatId, "Sənin açar sözlərin: \n`{$keywords}`");
                return;

            case '/help':
                $this->sendMessage($chatId, "Əmrlər:\n/start – Başla\n/my_keywords – Açar sözlərini göstər\n/help – Yardım");
                return;
        }

        // Əgər komanda deyilsə və mətn varsa: Açar sözləri kimi qəbul et
        if (!str_starts_with($text, '/')) {
            $keywords = array_map('trim', explode(',', $text));
            $user->update(['keywords' => json_encode($keywords)]);

            $this->sendMessage($chatId, "Təşəkkürlər! Açar sözlərin yadda saxlanıldı. ✅");
            return;
        }

        // Əgər qeyri-müəyyən komanda gəlirsə
        $this->sendMessage($chatId, "Tanımadım bu komandanı. /help yaza bilərsən.");
    }


    public function sendMessage($chatId, $text)
    {
        Http::post(env('TELEGRAM_URL'), [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
