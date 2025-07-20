<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;


class SendTelegramMessage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id)
            ->content("Yeni iş elanı gəldi!\n*Başlıq:* Backend Developer\n*Link:* https://... ")
            ->options([
                'parse_mode' => 'Markdown',
            ]);
    }
}
