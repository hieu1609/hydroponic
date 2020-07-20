<?php include "data.php" ?>

<?php
session_start();
$setdvice = false;

/*session is started if you don't write this line can't use $_Session  global variable*/
if (isset($_POST["selectDevice"])) {
    $device = $_POST["selectDevice"];
    $_SESSION["device"] = $device;

    $dt = new Data();
    $dt->RenderButton($_SESSION["device"]);
} else {
    $dt = new Data();
    $device = $dt->GetDefaultDevice();
    if (!$_SESSION["device"]) {
        $_SESSION["device"] = $device;
    }
}

if (isset($_POST["pump"])) {
    $dt = new Data();
    $status;
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
    }
    $dt->UpdatePump($_SESSION["device"], $status);
    // echo "Mực nước: {$row['water']}";
}

if (isset($_POST["water_in"])) {
    $dt = new Data();
    $status;
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
    }
    $dt->UpdateWaterIn($_SESSION["device"], $status);
    // echo "Mực nước: {$row['water']}";
}

if (isset($_POST["water_out"])) {
    $dt = new Data();
    $status;
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
    }
    $dt->UpdateWaterOut($_SESSION["device"], $status);
    // echo "Mực nước: {$row['water']}";
}

if (isset($_POST["mix"])) {
    $dt = new Data();
    $status;
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
    }
    $dt->UpdateMix($_SESSION["device"], $status);
    // echo "Mực nước: {$row['water']}";
}


if (isset($_POST["ppm_auto"])) {
    $dt = new Data();
    $status;
    $nutrientID;
    if (isset($_POST["nutrientID"])) {
        $nutrientID = $_POST["nutrientID"];
    }
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
    }
    $dt->UpdatePpmAutomatic($_SESSION["device"], $nutrientID, $status);
    // echo "Mực nước: {$row['water']}";
}

if (isset($_POST["pump_auto"])) {
    $dt = new Data();
    $status;
    $time_on;
    $time_off;
    if (isset($_POST["time_on"])) {
        $time_on = $_POST["time_on"];
    }

    if (isset($_POST["time_off"])) {
        $time_off = $_POST["time_off"];
    }

    if (isset($_POST["status"])) {
        $status = $_POST["status"];
    }
    $dt->UpdatePumpAutomatic($_SESSION["device"], $time_on, $time_off, $status);
    // echo "Mực nước: {$row['water']}";
}






if (isset($_POST["water"])) {
    $sql = "SELECT `water` FROM `sensors` WHERE `device_id` = {$_SESSION["device"]} ORDER BY `id` DESC LIMIT 1";
    $db = new Database();
    $sensor = $db->select($sql);
    $row = mysqli_fetch_array($sensor);
    echo "Mực nước: {$row['water']}";
}
if (isset($_POST["ec"])) {
    $sql = "SELECT `EC` FROM `sensors` WHERE `device_id` = {$_SESSION["device"]}  ORDER BY `id` DESC LIMIT 1";
    $db = new Database();
    $sensor = $db->select($sql);
    $row = mysqli_fetch_array($sensor);
    echo "Độ dẫn điện: {$row['EC']}";
}
if (isset($_POST["temp"])) {
    $sql = "SELECT `temperature` FROM `sensors` WHERE `device_id` = {$_SESSION["device"]}  ORDER BY `id` DESC LIMIT 1";
    $db = new Database();
    $sensor = $db->select($sql);
    $row = mysqli_fetch_array($sensor);
    echo "Nhiệt độ: {$row['temperature']}";
}
if (isset($_POST["pumpstatus"])) {
    $sql = "SELECT `pump` FROM `sensors` WHERE `device_id` = {$_SESSION["device"]}  ORDER BY `id` DESC LIMIT 1";
    $db = new Database();
    $sensor = $db->select($sql);
    $row = mysqli_fetch_array($sensor);
    if ($row['pump'] == 1) {
        $statusPump  = "BẬT";
    } else {
        $statusPump  = "TẮT";
    }
    echo "Bơm: {$statusPump}";
}
if (isset($_POST["ppm"])) {
    $sql = "SELECT `PPM` FROM `sensors` WHERE `device_id` = {$_SESSION["device"]}  ORDER BY `id` DESC LIMIT 1";
    $db = new Database();
    $sensor = $db->select($sql);
    $row = mysqli_fetch_array($sensor);
    echo "PPM: {$row['PPM']}";
}
if (isset($_POST["ppmstatus"])) {
    $sql = "SELECT * FROM `ppm_automatic` WHERE `device_id` = {$_SESSION["device"]}";
    $db = new Database();
    $ppmAuto = $db->select($sql);
    while ($row = $ppmAuto->fetch_assoc()) {
        if ($row['auto_mode'] == 1) {
            $statusPPMAuto  = "BẬT";
        } else {
            $statusPPMAuto  = "TẮT";
        }
    }
    echo "Dinh dưỡng: {$statusPPMAuto}";
}

?>