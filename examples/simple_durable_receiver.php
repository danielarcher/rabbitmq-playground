<?php 

require_once __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use RabbitPG\Receiver;

$connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');

$receiver = new Receiver($connection);

$receiver->getFromQueueDurable('hello-durable', function(AMQPMessage $message){
    echo $message->getBody()."\n";
});