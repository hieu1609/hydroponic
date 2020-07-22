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

if (isset($_POST["selectDeviceAuto"])) {
    $device = $_POST["selectDeviceAuto"];
    $_SESSION["device"] = $device;

    $dt = new Data();
    $dt->RenderAutoControlButton($_SESSION["device"]);
}

if (isset($_POST["pump"])) {
    $dt = new Data();
    $status;
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
        if ($status == 1) {
            $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_pump_on.py";
            exec($file_name);
        } else {
            $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_pump_off.py";
            exec($file_name);
        }
    }
}

if (isset($_POST["water_in"])) {
    $dt = new Data();
    $status;
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
        if ($status == 1) {
            $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_water_in_on.py";
            exec($file_name);
        } else {
            $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_water_in_off.py";
            exec($file_name);
        }
    }
}

if (isset($_POST["water_out"])) {
    $dt = new Data();
    $status;
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
        if ($status == 1) {
            $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_water_out_on.py";
            exec($file_name);
        } else {
            $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_water_out_off.py";
            exec($file_name);
        }
    }
}

if (isset($_POST["mix"])) {
    $dt = new Data();
    $status;
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
        if ($status == 1) {
            $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_mix_on.py";
            exec($file_name);
        } else {
            $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_mix_off.py";
            exec($file_name);
        }
    }
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
        echo $status;
    }
    $dt->UpdatePpmAutomatic($_SESSION["device"], $nutrientID, $status);

    // if ($status == 1) {
    //     $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_ppm_automatic.py";
    //     exec($file_name);
    // }
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
        echo $status;
    }
    $dt->UpdatePumpAutomatic($_SESSION["device"], $time_on, $time_off, $status);

    // if ($status == 1) {
    //     $file_name = "sudo python3 /var/www/html/web/" . $_SESSION["device"]  . "_pump_automatic.py";
    //     echo $status;
    //     exec($file_name);
    // }
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