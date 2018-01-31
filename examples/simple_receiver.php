<?php 

require_once __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use RabbitPG\Receiver;

$connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');

$receiver = new Receiver($connection);

class EmailSender
{
    public function __invoke(AMQPMessage $message)
    {
        echo $message->getBody()."\n";
    }
}

$receiver->getFromQueue('hello', (new EmailSender));