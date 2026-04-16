<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use App\Models\News;

class BreakingNewsNotification extends Notification
{
    use Queueable;

    public $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        $imageUrl = $this->news->cover_image ? asset('storage/images/' . $this->news->cover_image) : null;
        
        return (new WebPushMessage)
            ->title('Portal Notícias - Breaking News 🔴')
            ->icon('/images/pwa/icon-192x192.png')
            ->body($this->news->title)
            ->action('Ler Agora', 'explore')
            ->data(['url' => url('/' . $this->news->slug)])
            // Adiciona a cover image no Push se compatível (Chrome Android/Desktop)
            ->image($imageUrl);
    }
}
