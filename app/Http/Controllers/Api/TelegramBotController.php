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
        $chatId = $message['chat']['id'];
        $text = strtolower($message['text']);

        $user = TelegramUser::firstOrCreate(['chat_id' => $chatId], [
            'username' => $message['from']['username'] ?? null,
        ]);

        if ($text === '/start') {
            $this->sendMessage($chatId, "Salam! Açar sözlərinizi vergüllə ayıraraq göndərin. Məs: PHP, Java, Python, Devops");
            return;
        }

        // Sadəcə açar sözləri saxla
        $keywords = array_map('trim', explode(',', $text));
        $user->update(['keywords' => $keywords]);

        $this->sendMessage($chatId, "Təşəkkürlər! Açar sözləriniz yadda saxlanıldı.");
    }

    public function sendMessage($chatId, $text)
    {
        Http::post(env('TELEGRAM_URL'), [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
