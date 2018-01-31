<?php 

require_once __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use RabbitPG\Sender;

$connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');
$faker = Faker\Factory::create();
$loops = $faker->numberBetween(1,1000);

$sender = new Sender($connection);
for ($i=0; $i < $loops; $i++) { 
	$sender->sendToQueueDurable('hello-durable', new AMQPMessage("Hi {$faker->name()}!"));
}
