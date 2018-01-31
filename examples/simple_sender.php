<?php 

require_once __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use RabbitPG\Sender;

$connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');
$faker = Faker\Factory::create();

$sender = new Sender($connection);
$sender->sendToQueue('hello', new AMQPMessage("Hi {$faker->name()}!"));
unset($sender);