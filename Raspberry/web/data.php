<?php include "database.php" ?>
<?php
	$test = new Data();
    // $test->GetAllDevices();
    // $deviceId = 5;
    // $test->GetSensor($deviceId);
    // $test->GetPumpAutomatic();
    // $test->GetPpmAutomatic();
    // $test->GetNutrients();
	class Data{
		public function GetAllDevices() {
            $sql = "SELECT * FROM `devices`";
            $db = new Database();
            $devices = $db->select($sql);
            while ($row = $devices->fetch_assoc()) {
                echo $row['device_id']."<br>";
            }
			return $devices;
        }
        
        public function GetSensor($deviceId) {
            $sql = "SELECT * FROM `sensors` WHERE `device_id` = {$deviceId} ORDER BY `id` DESC LIMIT 1";
            $db = new Database();
            $sensor = $db->select($sql);
            while ($row = $sensor->fetch_assoc()) {
                echo $row['id']."<br>";
                echo $row['device_id']."<br>";
                echo $row['temperature']."<br>";
                echo $row['humidity']."<br>";
                echo $row['light']."<br>";
                echo $row['EC']."<br>";
                echo $row['PPM']."<br>";
                echo $row['water']."<br>";
                echo $row['pump']."<br>";
                echo $row['water_in']."<br>";
                echo $row['water_out']."<br>";
                echo $row['mix']."<br>"."<br>";
            }
			return $sensor;
        }
        
        public function GetPumpAutomatic() {
            $sql = "SELECT * FROM `pump_automatic`";
            $db = new Database();
            $pumpAuto = $db->select($sql);
            while ($row = $pumpAuto->fetch_assoc()) {
                echo $row['device_id']."<br>";
                echo $row['time_on']."<br>";
                echo $row['time_off']."<br>";
                echo $row['auto']."<br>"."<br>";
            }
			return $pumpAuto;
        }

        public function GetPpmAutomatic() {
            $sql = "SELECT * FROM `ppm_automatic`";
            $db = new Database();
            $ppmAuto = $db->select($sql);
            while ($row = $ppmAuto->fetch_assoc()) {
                echo $row['device_id']."<br>";
                echo $row['nutrient_id']."<br>";
                echo $row['auto_mode']."<br>";
                echo $row['auto_status']."<br>"."<br>";
            }
			return $ppmAuto;
        }

        public function GetNutrients() {
            $sql = "SELECT * FROM `nutrients`";
            $db = new Database();
            $nutrients = $db->select($sql);
            while ($row = $nutrients->fetch_assoc()) {
                echo $row['id']."<br>";
                echo $row['plant_name']."<br>";
                echo $row['ppm_min']."<br>";
                echo $row['ppm_max']."<br>"."<br>";
            }
			return $nutrients;
        }
	}
?>