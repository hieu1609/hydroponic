<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="./web/css/style.css">
    <title>Document</title>
</head>
<?php
include "web/data.php";
$cp = new Data();
$device = $cp->GetDefaultDevice();
session_start();
$_SESSION["device"] = $device;


?>

<body>
    <h2>Thông số hệ thống thủy canh</h2>
    <div class="deviceList">
        <?php
        $device = $cp->GetAllDevices();


        ?>
    </div>
    <div class="row">
        <?php
        $cp->GetSensor();
        $cp->GetPpmAutomatic();
        ?>
        <script>
            function updateSensor() {
                $.ajax({
                    url: "./web/action.php",
                    method: "POST",
                    data: {
                        water: 1,

                    },
                    success: function(data) {
                        $(".water").html(data);
                    },
                });
                $.ajax({
                    url: "./web/action.php",
                    method: "POST",
                    data: {
                        ec: 1,

                    },
                    success: function(data) {
                        $(".ec").html(data);
                    },
                });
                $.ajax({
                    url: "./web/action.php",
                    method: "POST",
                    data: {
                        temp: 1,

                    },
                    success: function(data) {
                        $(".temp").html(data);
                    },
                });
                $.ajax({
                    url: "./web/action.php",
                    method: "POST",
                    data: {
                        pumpstatus: 1,

                    },
                    success: function(data) {
                        $(".pumpstatus").html(data);
                    },
                });
                $.ajax({
                    url: "./web/action.php",
                    method: "POST",
                    data: {
                        ppm: 1,

                    },
                    success: function(data) {
                        $(".ppm").html(data);
                    },
                });
                $.ajax({
                    url: "./web/action.php",
                    method: "POST",
                    data: {
                        ppmstatus: 1,

                    },
                    success: function(data) {
                        $(".ppmstatus").html(data);
                    },
                });
            }
            setInterval(function() {

                updateSensor();
            }, 5000);
        </script>
    </div>
    <div class="container">
        <div class="title">
            <h2>Điều khiển</h2>
        </div>
        <div class="controller" style="--i:0">
            <i class="fa fa-plus"></i>
            <div class="mode"></div>
        </div>
        <div class="controller" style="--i:1">
            <label class="btnname">Van nước vào</label>
            <button class="button" onclick="goPython()" href="">off</button>
            <script>
                function goPython() {
                    var id = 4;
                    var func = "pump.py";
                    var fileName = "./web/" + id + func;
                    console.log(fileName);
                    $
                    $.ajax({
                        type: 'POST',
                        dataType: "text",
                        url: fileName,
                        context: document.body
                    }).done(function() {
                        alert('finished python script');;
                    });
                }
            </script>
        </div>
        <div class="controller" style="--i:1">
            <label class="btnname">Van nước ra</label>
            <button class="button" href="">off</button>
        </div>
        <div class="controller" style="--i:1">
            <label class="btnname">Motor trộn</label>
            <button class="button" href="">off</button>
        </div>
        <div class="controller" style="--i:1">
            <label class="btnname">Bơm</label>
            <button class="button" href="">off</button>
        </div>
        <div class="controller" style="--i:1">
            <label class="btnname">Pha tự động</label>
            <button class="button" href="">off</button>
        </div>
        <!-- <div class="controller" style="--i:2">
            <label class="btnname">title</label>
            <input type="range" id="rangeValue" class="range" name="" value="0" min="0" max="255">
        </div>
        <div class="controller" style="--i:3">
            <label class="btnname">title</label>
            <button class="button" href="">off</button>
            <input type="range" id="rangeValue" class="range" name="" value="0" min="0" max="255">
        </div> -->
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="./web/main.js"></script>

</html>