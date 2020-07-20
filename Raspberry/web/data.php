<?php include "database.php" ?>
<?php
$test = new Data();
// $test->GetAllDevices();
// $deviceId = 5;
// $test->GetSensor($deviceId);
// $test->GetPumpAutomatic();
// $test->GetPpmAutomatic();
// $test->GetNutrients();   
// $test->GetSensor(5);
class Data
{
    public static $dv;
    public function GetDefaultDevice()
    {
        $sql = "SELECT * FROM `devices`  ORDER BY `device_id` ASC LIMIT 1";
        $db = new Database();
        $device = $db->select($sql);
        $row = $device->fetch_assoc();
        Data::$dv = $row['device_id'];
        return Data::$dv;
    }

    public function GetAllDevices()
    {
        $sql = "SELECT * FROM `devices`";
        $db = new Database();
        $devices = $db->select($sql);
        while ($row = $devices->fetch_assoc()) {
            // echo $row['device_id'] . "<br>";


            $category = <<<DELIMITER
            <button class="btn btn-sucess" id="device" device='{$row['device_id']}'> Thiết bị {$row['device_id']} </button>
           
        DELIMITER;
            echo $category;
        }
        // return $defaultDevice;
    }

    public function GetSensor()
    {
        $deviceId = Data::$dv;
        $sql = "SELECT * FROM `sensors` WHERE `device_id` = {$deviceId} ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $sensor = $db->select($sql);
        while ($row = $sensor->fetch_assoc()) {
            // echo $row['id'] . "<br>";
            // echo $row['device_id'] . "<br>";
            // echo $row['temperature'] . "<br>";
            // echo $row['humidity'] . "<br>";
            // echo $row['light'] . "<br>";
            // echo $row['EC'] . "<br>";
            // echo $row['PPM'] . "<br>";
            // echo $row['water'] . "<br>";
            // echo $row['pump'] . "<br>";
            // echo $row['water_in'] . "<br>";
            // echo $row['water_out'] . "<br>";
            // echo $row['mix'] . "<br>" . "<br>";
            if ($row['pump'] == 1) {
                $statusPump  = "BẬT";
            } else {
                $statusPump  = "TẮT";
            }
            $category = <<<DELIMITER
            <div class="card card-1 .col-sm-8 .col-md-6 .col-lg-4">
            <i class="fa fa-water"></i>
            <h3 class="water">Mực nước: {$row['water']} </h3>
            </div>
            <div class="card card-1 .col-sm-8 .col-md-6 .col-lg-4">
            <i class="fa fa-bolt"></i>
            <h3 class="ec">Độ dẫn điện: {$row['EC']} </h3>
            </div>
            <div class="card card-1 .col-sm-8 .col-md-6 .col-lg-4">
            <i class="fa fa-temperature-low"></i>
            <h3 class="temp">Nhiệt độ: {$row['temperature']} </h3>
            </div>
            <div class="card card-1 .col-sm-8 .col-md-6 .col-lg-4">
            <i class="fa fa-fill-drip"></i>
            <h3 class="pumpstatus">Bơm: {$statusPump} </h3>
            </div>
            <div class="card card-1 .col-sm-8 .col-md-6 .col-lg-4">
            <i class="fa fa-vial"></i>
            <h3 class="ppm">PPM: {$row['PPM']} </h3>
            </div>
            
            
        DELIMITER;


            echo $category;
        }
        return $sensor;
    }

    public function GetPumpAutomatic()
    {
        $sql = "SELECT * FROM `pump_automatic`";
        $db = new Database();
        $pumpAuto = $db->select($sql);
        while ($row = $pumpAuto->fetch_assoc()) {
            echo $row['device_id'] . "<br>";
            echo $row['time_on'] . "<br>";
            echo $row['time_off'] . "<br>";
            echo $row['auto'] . "<br>" . "<br>";
        }
        return $pumpAuto;
    }

    public function GetPpmAutomatic()
    {

        $deviceId = Data::$dv;
        $sql = "SELECT * FROM `ppm_automatic` WHERE `device_id` = {$deviceId}";
        $db = new Database();
        $ppmAuto = $db->select($sql);
        while ($row = $ppmAuto->fetch_assoc()) {
            // echo $row['device_id'] . "<br>";
            // echo $row['nutrient_id'] . "<br>";
            // echo $row['auto_mode'] . "<br>";
            // echo $row['auto_status'] . "<br>" . "<br>";

            if ($row['auto_mode'] == 1) {
                $statusPPMAuto  = "BẬT";
            } else {
                $statusPPMAuto  = "TẮT";
            }
            $category = <<<DELIMITER
            <div class="card card-1 .col-sm-8 .col-md-6 .col-lg-4">
            <i class="fa fa-fill-drip"></i>
            <h3 class="ppmstatus">Dinh dưỡng: {$statusPPMAuto} </h3>
            </div>
           
        DELIMITER;
        }
        echo $category;
        return $ppmAuto;
    }

    public function GetNutrients()
    {
        $sql = "SELECT * FROM `nutrients`";
        $db = new Database();
        $nutrients = $db->select($sql);
        while ($row = $nutrients->fetch_assoc()) {
            echo $row['id'] . "<br>";
            echo $row['plant_name'] . "<br>";
            echo $row['ppm_min'] . "<br>";
            echo $row['ppm_max'] . "<br>" . "<br>";
        }
        return $nutrients;
    }
}
?>