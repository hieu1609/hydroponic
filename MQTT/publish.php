<?php
require 'phpMQTT.php';

$host = 'maqiatto.com';
$port = 1883;
$user = 'thuycanhiot@gmail.com';
$pass = 'Lancuoi1234@';
$topic = 'thuycanhiot@gmail.com/6=pump';
$client_id = "publisher";

$message = "1";

$mqtt = new Bluerhinos\phpMQTT($host, $port, $client_id);
if ($mqtt->connect(true, NULL, $user, $pass)) {
    $mqtt->publish($topic, $message, 0);
    echo "Published message: " . $message;
    $mqtt->close();
}else{
    echo "Fail or time out<br />";
}
