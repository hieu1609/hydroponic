const body = document.querySelector("body");
const full = document.querySelector(".container");
const circles = document.querySelectorAll(".mode");
const btns = document.querySelectorAll(".button");
for (const btn of btns) {
  btn.addEventListener("click", () => {
    btn.classList.toggle("pushed");
    var state = btn.innerText;
    console.log(state);
    state = state == "ON" ? "OFF" : "ON";
    btn.innerText = state;
  });
}
for (const circl of circles) {
  circl.style.backgroundColor = circl.innerText;
  circl.addEventListener("click", () => {
    body.classList.toggle("dark");
  });
}

$(document).ready(function () {
  $("body").delegate("#device", "click", function () {
    var device = $(this).attr("device");
    console.log(device);
    $.ajax({
      url: "./web/action.php",
      method: "POST",
      data: { selectDevice: device },
      success: function (data) {
        console.log(data);
      },
    });
  });
});
