const body = document.querySelector("body");
const full = document.querySelector(".container");
const circles = document.querySelectorAll(".mode");
var btns = document.querySelectorAll(".button");
for (const btn of btns) {
  btn.addEventListener("click", () => {
    btn.classList.toggle("pushed");
    var state = btn.innerText;
    console.log(state);
    state = state == "BẬT" ? "TẮT" : "BẬT";
    btn.innerText = state;
  });
}
for (const circl of circles) {
  circl.style.backgroundColor = circl.innerText;
  circl.addEventListener("click", () => {
    body.classList.toggle("dark");
  });
}
$("body").delegate("#device", "click", function () {
  var device = $(this).attr("device");
  console.log(device);
  $.ajax({
    url: "./web/action.php",
    method: "POST",
    data: { selectDevice: device },
    success: function (data) {
      $("#controlButton").html(data);
      btns = document.querySelectorAll(".button");
      for (const btn of btns) {
        btn.addEventListener("click", () => {
          btn.classList.toggle("pushed");
          var state = btn.innerText;
          console.log(state);
          state = state == "BẬT" ? "TẮT" : "BẬT";
          btn.innerText = state;
        });
      }
    },
  });
});

$("body").delegate("#device", "click", function () {
  var device = $(this).attr("device");
  console.log(device);
  $.ajax({
    url: "./web/action.php",
    method: "POST",
    data: { selectDeviceAuto: device },
    success: function (data) {
      $("#controlAutoButton").html(data);
      btns = document.querySelectorAll(".button");
      for (const btn of btns) {
        btn.addEventListener("click", () => {
          btn.classList.toggle("pushed");
          var state = btn.innerText;
          console.log(state);
          state = state == "BẬT" ? "TẮT" : "BẬT";
          btn.innerText = state;
        });
      }
    },
  });
});

const selectElement = document.querySelector(".nutrients");
selectElement.addEventListener("change", (event) => {
  console.log(event.target.value);
});

$("body").delegate("#ppm_auto", "click", function () {
  var nutrientID = document.getElementById("nutrients").value;
  var statusText = document.getElementById("ppm_auto");
  console.log(nutrientID);
  if (statusText.innerText == "TẮT") {
    status = 1;
  } else {
    status = 0;
  }
  $.ajax({
    url: "./web/action.php",
    method: "POST",
    data: { ppm_auto: 1, status: status, nutrientID: nutrientID },
    success: function (data) {
      console.log(data);
    },
  });
});
$("body").delegate("#pump_auto", "click", function () {
  var time_on = document.getElementById("time_on").value;
  var time_off = document.getElementById("time_off").value;
  var statusText = document.getElementById("pump_auto");
  console.log(time_on);
  console.log(time_off);
  if (statusText.innerText == "TẮT") {
    status = 1;
  } else {
    status = 0;
  }
  $.ajax({
    url: "./web/action.php",
    method: "POST",
    data: {
      pump_auto: 1,
      status: status,
      time_on: time_on,
      time_off: time_off,
    },
    success: function (data) {
      console.log(data);
    },
  });
});
$(document).ready(function () {
  $("body").delegate("#pump", "click", function () {
    var statusText = document.getElementById("pump");
    var status = 0;
    console.log(statusText.innerText);
    if (statusText.innerText == "TẮT") {
      status = 1;
      console.log(status);
    } else {
      status = 0;
      console.log(status);
    }
    $.ajax({
      url: "./web/action.php",
      method: "POST",
      data: { pump: 1, status: status },
      success: function (data) {
        console.log(data);
      },
    });
  });

  $("body").delegate("#water_in", "click", function () {
    var statusText = document.getElementById("water_in");
    var status = 0;
    console.log(statusText.innerText);
    if (statusText.innerText == "TẮT") {
      status = 1;
      console.log(status);
    } else {
      status = 0;
      console.log(status);
    }
    $.ajax({
      url: "./web/action.php",
      method: "POST",
      data: { water_in: 1, status: status },
      success: function (data) {
        console.log(data);
      },
    });
  });

  $("body").delegate("#water_out", "click", function () {
    var statusText = document.getElementById("water_out");
    var status = 0;
    console.log(statusText.innerText);
    if (statusText.innerText == "TẮT") {
      status = 1;
      console.log(status);
    } else {
      status = 0;
      console.log(status);
    }
    $.ajax({
      url: "./web/action.php",
      method: "POST",
      data: { water_out: 1, status: status },
      success: function (data) {
        console.log(data);
      },
    });
  });

  $("body").delegate("#mix", "click", function () {
    var statusText = document.getElementById("mix");
    var status = 0;
    console.log(statusText.innerText);
    if (statusText.innerText == "TẮT") {
      status = 1;
      console.log(status);
    } else {
      status = 0;
      console.log(status);
    }
    $.ajax({
      url: "./web/action.php",
      method: "POST",
      data: { mix: 1, status: status },
      success: function (data) {
        console.log(data);
      },
    });
  });
});
