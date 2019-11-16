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
  statusPump: boolean = false;
  statusPumpAuto: boolean = false;
  pumpStatusHTML: string = "OFF";
  pumpAutoStatusHTML: string = "OFF";
  weather: any = [];
  ngOnInit() {
    this.getCurrentWeather();
  }

  PumpOn() {
    this.statusPump = true;
    this.pumpStatusHTML = "ON";
    this.statusPumpAuto = false;
    this.pumpAutoStatusHTML = "OFF";
    const uri = "user/controlPump";
    const message = {
      devicesId: 2,
      message: "1"
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        console.log(data);
        // alert("Đăng nhập thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  PumpOff() {
    this.statusPump = false;
    this.pumpStatusHTML = "OFF";
    const uri = "user/controlPump";
    const message = {
      devicesId: 2,
      message: "0"
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        console.log(data);
        // alert("Đăng nhập thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
      },
      (err: any) => {
        console.log(err);
      }
    );
  }

  PumpAutoOn() {
    this.statusPumpAuto = true;
    this.pumpAutoStatusHTML = "ON";
    const uri = "user/pumpAutoOn";
    const message = {
      devicesId: 2,
      timeOn: 3,
      timeOff: 2
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        console.log(data);
        // alert("Đăng nhập thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  PumpAutoOff() {
    this.statusPumpAuto = false;
    this.pumpAutoStatusHTML = "OFF";
    const uri = "user/pumpAutoOff";
    const message = {
      devicesId: 2
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        console.log(data);
        // alert("Đăng nhập thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
      },
      (err: any) => {
        console.log(err);
      }
    );
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
