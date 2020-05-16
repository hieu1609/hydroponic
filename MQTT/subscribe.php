<?php
require 'phpMQTT.php';
set_time_limit(0);

$host = 'm24.cloudmqtt.com';
$port = 15217;
$user = 'tmlgemnz';
$pass = '7fub13-eRIeR';
$topic = 'update';
$client_id = "phpMQTT-subscriber";

function procmsg($topic, $msg){
  $now = new DateTime();
  $now->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
  $time = $now->format('Y-m-d H:i:s');
  echo $time;
  $array = explode("=", $msg);
  if($array[10] == null){
	echo "0";
  }
  else {
	//6=26.11=42.35=558=1.40=753=56.20=1=0=0=0
	$conn = mysqli_connect("localhost", "root", "", "hydroponic");
	$query = "INSERT INTO sensors (id, device_id, temperature, humidity, light, EC, PPM, water, pump, water_in, water_out, mix, created_at, updated_at)
	VALUES (null, '$array[0]', '$array[1]', '$array[2]', '$array[3]', '$array[4]', '$array[5]', '$array[6]', '$array[7]', '$array[8]', '$array[9]', '$array[10]', '$time', '$time')";
	$dta = mysqli_query($conn, $query);
	if ($dta ) {
		echo "1";
	}
  }
  sleep(3);
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
