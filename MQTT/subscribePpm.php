<?php
require 'phpMQTT.php';
set_time_limit(0);

$host = 'maqiatto.com';
$port = 1883;
$user = 'thuycanhiot@gmail.com';
$pass = 'Lancuoi1234@';
$topic = 'thuycanhiot@gmail.com/updatePpm';
$client_id = "subscriber1";

function procmsg($topic, $msg){
  $now = new DateTime();
  $now->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
  $time = $now->format('Y-m-d H:i:s');
  echo $time;
  echo "\n";
  echo $msg;
  echo "\n";
  $array = explode("=", $msg);
  if($array[3] == null){
    echo "0";
    echo "\n";
  }
  else {
    //4=5=10=1
    $conn = mysqli_connect("localhost", "root", "", "hydroponic");
    $query = "UPDATE `ppm_automatic` SET `nutrient_id`={$array[1]},`auto_mode`={$array[2]},
    `auto_status`={$array[3]} WHERE `device_id` = {$array[0]}";
    $dta = mysqli_query($conn, $query);
    if ($dta) {
      echo "1";
      echo "\n";
    }
  }
}

$mqtt = new Bluerhinos\phpMQTT($host, $port, $client_id);
if ($mqtt->connect(true, NULL, $user, $pass)) {
  $topics[$topic] = array(
      "qos" => 0,
      "function" => "procmsg"
  );
  
  $mqtt->subscribe($topics,0);
  while($mqtt->proc()) {}
  $mqtt->close();

} 
else {
  exit(1);
}
