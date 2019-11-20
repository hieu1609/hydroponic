import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";

@Component({
  selector: "app-statistics",
  templateUrl: "./statistics.component.html",
  styleUrls: ["./statistics.component.scss"]
})
export class StatisticsComponent implements OnInit {
  constructor(private _dataService: DataService, private router: Router) {}

  weather: any = [];
  devices: any = [];
  nutrients: any = [];
  nutrient: any = {};
  veGetType: any = [
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false
  ];
  ngOnInit() {
    this.getCurrentWeather();
    this.getDeviceID();
    this.getNutrients();
  }

  getCurrentWeather() {
    const uri = "weather/currentweather";
    this._dataService.post(uri, "").subscribe(
      (data: any) => {
        console.log(data);
        this.weather = data.data;

        // alert("Đăng nhập thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  getDeviceID() {
    const uri = "devices/getDeviceIdForUser";
    this._dataService.get(uri).subscribe(
      (data: any) => {
        console.log(data);
        this.devices = data.data;

        // alert("Đăng nhập thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
      },
      (err: any) => {
        console.log(err);
      }
    );
  }

  getNutrients() {
    const uri = "user/getNutrients";
    this._dataService.get(uri).subscribe(
      (data: any) => {
        console.log("aaa");
        this.nutrients = data.data;
        this.nutrient = this.nutrients[0];
        for (let index = 0; index < this.nutrients.length; index++) {
          console.log(this.nutrients[index]);
        }

        // alert("Đăng nhập thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
      },
      (err: any) => {
        console.log(err);
      }
    );
  }

  ShowVegetInfo(type) {
    console.log("ssss");

    for (let i = 0; i < 10; i++) {
      this.veGetType[i] = false;
    }
    this.veGetType[type] = true;
    this.nutrient = this.nutrients[type];
  }
  // getWeatherForecast() {
  //   const uri = "weather/currentweather";
  //   this._dataService.post(uri, "").subscribe(
  //     (data: any) => {
  //       console.log(data);
  //       this.weather = data.data;

  //       // alert("Đăng nhập thành công !");
  //       // localStorage.setItem("user", JSON.stringify(data));
  //     },
  //     (err: any) => {
  //       console.log(err);
  //     }
  //   );
  // }
}
