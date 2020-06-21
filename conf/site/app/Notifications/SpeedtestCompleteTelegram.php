<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class SpeedtestCompleteTelegram extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($speedtest)
    {
        $speedtest->ping = number_format((float)$speedtest->ping, 1, '.', '');
        $speedtest->download = number_format((float)$speedtest->download, 1, '.', '');
        $speedtest->upload = number_format((float)$speedtest->upload, 1, '.', '');
        $this->speedtest = $speedtest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            TelegramChannel::class
        ];
    }

    /**
     * Format tekegram notification
     *
     * @param   mixed   $notifiable
     * @return  TelegramMessage
     */
    public function toTelegram($notifiable)
    {
        $speedtest = $this->speedtest;
        $msg = "*New Speedtest*
Ping: *$speedtest->ping*
Download: *$speedtest->download*
Upload: *$speedtest->upload*";
        return TelegramMessage::create()
                              ->to(env('TELEGRAM_CHAT_ID'))
                              ->content($msg)
                              ->options(['parse_mode' => 'Markdown']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
