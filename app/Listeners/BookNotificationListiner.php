<?php

namespace App\Listeners;

use App\Events\BookNotificationEvent;
use App\Helpers\RabbitMqHelper;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookNotificationListiner implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BookNotificationEvent $event)
    {
        RabbitMqHelper::sendMessageBasic("book_notifications", $event);
    }
}
