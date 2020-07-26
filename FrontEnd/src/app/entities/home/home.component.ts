import { Component, OnInit } from "@angular/core";
import * as $ from "jquery";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import Swal from "sweetalert2";

@Component({
  selector: "app-home",
  templateUrl: "./home.component.html",
  styleUrls: ["./home.component.scss"],
})
export class HomeComponent implements OnInit {
  constructor(private _dataService: DataService, private router: Router) { }

  ngOnInit() {
    $("#nav-1 a").on("click", function () {
      var position = $(this).parent().position();
      var width = $(this).parent().width();
      $("#nav-1 .slide1").css({
        opacity: 1,

        left: +position.left,
        width: width,
      });
    });

    $("#nav-1 a").on("mouseover", function () {
      var position = $(this).parent().position();
      var width = $(this).parent().width();
      $("#nav-1 .slide2")
        .css({
          opacity: 1,
          left: +position.left,
          width: width,
        })
        .addClass("squeeze");
    });

    $("#nav-1 a").on("mouseout", function () {
      $("#nav-1 .slide2").css({ opacity: 0 }).removeClass("squeeze");
    });

    var currentWidth = $("#nav-1 .nav").find(".active").parent("li").width();
    var current = $(".nav .active").position();
    $("#nav-1 .slide1").css({ left: +current.left, width: currentWidth });
  }
  logOut() {
    const uri = "auth/logout";

    this._dataService.post(uri).subscribe(
      (data: any) => {
        console.log(data);
        // alert("Đăng xuất thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
        Swal.fire({
          icon: "success",
          title: "Successul",
          text: "Đăng xuất thành công",
          showConfirmButton: false,
          timer: 1500,
        });
        this.router.navigate([""]);
        // sessionStorage.removeItem("deviceID");
        // sessionStorage.removeItem("nutrients");
        // sessionStorage.removeItem("weather");
        // sessionStorage.removeItem("sensorData");

        sessionStorage.clear();
        localStorage.removeItem("user");
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
}
