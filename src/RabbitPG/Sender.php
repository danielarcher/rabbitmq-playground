<?php 

namespace RabbitPG;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Sender
{
    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        $this->channel = $connection->channel();
    }

    public function sendToQueue(string $queue, AMQPMessage $message)
    {
        $this->channel->queue_declare($queue, false, false, false, false);
        $this->channel->basic_publish($message, '', $queue);
    }

    public function sendToQueueDurable(string $queue, AMQPMessage $message)
    {
        $this->channel->queue_declare($queue, false, true, false, false);
        $this->channel->basic_publish($message, '', $queue);
    }

    public function sendToExchangeDirect(string $exchangeName, string $routingKey, AMQPMessage $message)
    {
        $this->channel->exchange_declare($exchangeName, 'direct', false, false, false);
        $this->channel->basic_publish($message, $exchangeName, $routingKey);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}