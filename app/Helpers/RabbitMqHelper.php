<?php

namespace App\Helpers;

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use Illuminate\Support\Facades\Log;

class RabbitMqHelper
{
    public static function sendMessageBasic(string $queue, array|string|object $message)
    {        
        $host = env('RABBITMQ_HOST');
        $port = env('RABBITMQ_PORT', 5672);
        $user = env('RABBITMQ_USER', 'admin');
        $password = env('RABBITMQ_PASSWORD', 'admin');

        $connection = new AMQPStreamConnection($host, $port, $user, $password);
        $channel = $connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
    
        $message = (is_string($message)) ? $message : json_encode($message);
        $message = new AMQPMessage($message);

        $channel->basic_publish($message, '', $queue);

        $channel->close();
        $connection->close();
    }
}