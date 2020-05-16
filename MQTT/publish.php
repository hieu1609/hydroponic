<?php
require 'phpMQTT.php';

$host = 'm24.cloudmqtt.com';
$port = 15217;
$user = 'tmlgemnz';
$pass = '7fub13-eRIeR';
$topic = 'update';
$client_id = "phpMQTT-publisher";

$message = "Hello CloudMQTT!";

$mqtt = new Bluerhinos\phpMQTT($host, $port, $client_id);
if ($mqtt->connect(true, NULL, $user, $pass)) {
    $mqtt->publish($topic, $message, 0);
    echo "Published message: " . $message;
    $mqtt->close();
}else{
    echo "Fail or time out<br />";
}
