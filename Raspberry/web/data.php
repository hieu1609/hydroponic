<?php include "database.php" ?>
<?php
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
            $category = <<<DELIMITER
            <button class="btn btn-sucess" id="device" device='{$row['device_id']}'> Thiết bị {$row['device_id']} </button>
        DELIMITER;
            echo $category;
        }
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
            <h3 class="water">Mực nước: {$row['water']} %</h3>
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
    public function RenderButton($deviceId)
    {
        $sql = "SELECT * FROM `sensors` WHERE `device_id` = {$deviceId} ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $sensor = $db->select($sql);
        while ($row = $sensor->fetch_assoc()) {
            if ($row['pump'] == 1) {
                $pump = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname">Bơm nước</label>
                    <button id="pump" class="button pushed">TẮT</button>
                    </div>                     
                DELIMITER;
                echo $pump;
            } else {
                $pump = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname">Bơm nước</label>
                    <button id="pump" class="button">BẬT</button>
                    </div>                     
                DELIMITER;
                echo $pump;
            }

            if ($row['water_in'] == 1) {
                $water_in = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname">Van nước vào</label>
                    <button id="water_in" class="button pushed">TẮT</button>
                    </div>                     
                DELIMITER;
                echo $water_in;
            } else {
                $water_in = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname">Van nước vào</label>
                    <button id="water_in" class="button">BẬT</button>
                    </div>                     
                DELIMITER;
                echo $water_in;
            }

            if ($row['water_out'] == 1) {
                $water_out = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname">Van nước ra</label>
                    <button id="water_out" class="button pushed">TẮT</button>
                    </div>                     
                DELIMITER;
                echo $water_out;
            } else {
                $water_out = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname">Van nước ra</label>
                    <button id="water_out" class="button">BẬT</button>
                    </div>                     
                DELIMITER;
                echo $water_out;
            }

            if ($row['mix'] == 1) {
                $mix = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname">Motor trộn</label>
                    <button id="mix" class="button pushed">TẮT</button>
                    </div>                     
                DELIMITER;
                echo $mix;
            } else {
                $mix = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname">Motor trộn</label>
                    <button id="mix" class="button">BẬT</button>
                    </div>                     
                DELIMITER;
                echo $mix;
            }
        }
        return $sensor;
    }

    public function RenderAutoControlButton($deviceId)
    {
        $sql = "SELECT * FROM `pump_automatic` WHERE `device_id` = {$deviceId} ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $sensor = $db->select($sql);
        while ($row = $sensor->fetch_assoc()) {
            if ($row['auto'] == 1) {
                $pump = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname" style="width:400px; text-align:center">Bơm Tuần Hoàn</label>
                    <div class="input">
                        <input type="text" id="time_on" placeholder="Thời gian bật">
                        <input type="text" id="time_off" placeholder="Thời gian tắt">
                    </div>
                    <button id="pump_auto" class="button pushed">TẮT</button>
                    </div>                     
                DELIMITER;
                echo $pump;
            } else {
                $pump = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname" style="width:400px; text-align:center">Bơm Tuần Hoàn</label>
                    <div class="input">
                        <input type="text" id="time_on" placeholder="Thời gian bật">
                        <input type="text" id="time_off" placeholder="Thời gian tắt">
                    </div>
                    <button id="ppm_auto" class="button">BẬT</button>
                    </div>                     
                DELIMITER;
                echo $pump;
            }
        }

        $sql = "SELECT * FROM `ppm_automatic` WHERE `device_id` = {$deviceId} ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $sensor = $db->select($sql);
        while ($row = $sensor->fetch_assoc()) {
            if ($row['auto_mode'] == 1) {
                $pumpAuto = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname" style="width:400px; text-align:center">Pha Tự Động</label>
                    <div class="input">
                        <select class="nutrients" id="nutrients" name="nutrients">
                            <option value=1 selected>Húng quế</option>
                            <option value=2>Bắp cải</option>
                            <option value=3>Cần tây</option>
                            <option value=4>Cải xoong</option>
                            <option value=5>Cải xanh</option>
                            <option value=6>Tía tô</option>
                            <option value=7>Bạc hà</option>
                            <option value=8>Cải bó xôi</option>
                            <option value=9>Húng lủi</option>
                            <option value=10>Rau muống</option>
                            <option value=11>Xà lách</option>
                        </select>
                    </div>
                    <button id="ppm_auto" class="button pushed">TẮT</button>
                </div>                     
            DELIMITER;
                echo $pumpAuto;
            } else {
                $ppmAuto = <<<DELIMITER
                    <div class="controller" style="--i:1">
                    <label class="btnname" style="width:400px; text-align:center">Pha Tự Động</label>
                    <div class="input">
                        <select class="nutrients" id="nutrients" name="nutrients">
                            <option value=1 selected>Húng quế</option>
                            <option value=2>Bắp cải</option>
                            <option value=3>Cần tây</option>
                            <option value=4>Cải xoong</option>
                            <option value=5>Cải xanh</option>
                            <option value=6>Tía tô</option>
                            <option value=7>Bạc hà</option>
                            <option value=8>Cải bó xôi</option>
                            <option value=9>Húng lủi</option>
                            <option value=10>Rau muống</option>
                            <option value=11>Xà lách</option>
                        </select>
                    </div>
                    <button id="ppm_auto" class="button">Bật</button>
                </div>                      
            DELIMITER;
                echo $ppmAuto;
            }
        }
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

    public function UpdatePumpAutomatic($deviceId, $timeOn, $timeOff, $auto)
    {
        $sql = "UPDATE `pump_automatic` SET `time_on`={$timeOn},`time_off`={$timeOff},
        `auto`={$auto} WHERE `device_id` = {$deviceId}";
        $db = new Database();
        $db->insert($sql);
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

    public function UpdatePump($deviceId, $status)
    {
        $sql = "UPDATE `relay` SET `pump`={$status} WHERE `device_id` = {$deviceId}";
        $db = new Database();
        $db->insert($sql);
    }

    public function UpdateWaterIn($deviceId, $status)
    {
        $sql = "UPDATE `relay` SET `water_in`={$status} WHERE `device_id` = {$deviceId}";
        $db = new Database();
        $db->insert($sql);
    }

    public function UpdateWaterOut($deviceId, $status)
    {
        $sql = "UPDATE `relay` SET `water_out`={$status} WHERE `device_id` = {$deviceId}";
        $db = new Database();
        $db->insert($sql);
    }

    public function UpdateMix($deviceId, $status)
    {
        $sql = "UPDATE `relay` SET `mix`={$status} WHERE `device_id` = {$deviceId}";
        $db = new Database();
        $db->insert($sql);
    }

    public function UpdatePpmAutomatic($deviceId, $nutrientId, $autoMode)
    {
        $sql = "UPDATE `ppm_automatic` SET `nutrient_id`={$nutrientId},`auto_mode`={$autoMode} WHERE `device_id` = {$deviceId}";
        $db = new Database();
        $db->insert($sql);
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

            return $nutrients;
        }
    }
}
?>