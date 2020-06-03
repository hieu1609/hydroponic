<?php
require 'phpMQTT.php';
set_time_limit(0);

$host = 'maqiatto.com';
$port = 1883;
$user = 'thuycanhiot@gmail.com';
$pass = 'Lancuoi1234@';
$topic = 'thuycanhiot@gmail.com/update';
$client_id = "subscriber";

function procmsg($topic, $msg){
  $now = new DateTime();
  $now->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
  $time = $now->format('Y-m-d H:i:s');
  echo $time;
  echo "\n";
  echo $msg;
  echo "\n";
  $array = explode("=", $msg);
  if($array[7] == null){
    echo "0";
    echo "\n";
  }
  else {
    $pump = $array[7][0];
    $water_in = $array[7][1];
    $water_out = $array[7][2];
    $mix = $array[7][3];
    //6=30=68=282=0.54=376=56=0000
    $conn = mysqli_connect("localhost", "root", "", "hydroponic");
    $query = "INSERT INTO sensors (id, device_id, temperature, humidity, light, EC, PPM, water, pump, water_in, water_out, mix, created_at, updated_at)
    VALUES (null, '$array[0]', '$array[1]', '$array[2]', '$array[3]', '$array[4]', '$array[5]', '$array[6]', '$pump', '$water_in', '$water_out', '$mix', '$time', '$time')";
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
