<?php 

namespace RabbitPG;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Receiver 
{
    private $connection;

    private $channel;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        $this->channel = $connection->channel();
    }

    public function getFromQueue(string $queue, callable $callback)
    {
        $this->channel->queue_declare($queue, false, false, false, false);
        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        };
    }

    public function getFromQueueDurable(string $queue, callable $callback)
    {
        $this->channel->queue_declare($queue, false, true, false, false);
        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        };
    }

    public function getFromExchangeDirect(string $exchange, array $routingKeys, callable $callback)
    {
        $this->channel->exchange_declare($exchange, 'direct', false, false, false);
        
        list($queue, ,) = $this->channel->queue_declare("", false, false, true, false);

        foreach ($routingKeys as $routingKey) {
            $this->channel->queue_bind($queue, $exchange, $routingKey);
        }
        
        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        };
    }

    private function bindRoutingKeys($routingKeys, $exchange) {
        
        return $this->channel;
    }
}