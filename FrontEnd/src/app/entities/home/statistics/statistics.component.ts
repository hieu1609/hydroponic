import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";

@Component({
  selector: "app-statistics",
  templateUrl: "./statistics.component.html",
  styleUrls: ["./statistics.component.scss"],
})
export class StatisticsComponent implements OnInit {
  constructor(private _dataService: DataService, private router: Router) { }
  forecast: any;
  forecastFlag: boolean = false;
  weather: any = [];
  devices: any = [];
  nutrients: any = [];
  nutrient: any = {};
  sensorObj: any = {};

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
    false,
  ];
  ngOnInit() {
    if (sessionStorage.getItem("nutrients")) {
      let data = JSON.parse(sessionStorage.getItem("nutrients"));
      this.nutrients = data.data;
      this.nutrient = this.nutrients[0];
    } else {
      this.getNutrients();
    }
    if (sessionStorage.getItem("deviceID")) {
      let data = JSON.parse(sessionStorage.getItem("deviceID"));
      this.devices = data.data;
    } else {
      this.getDeviceID();
    }
    if (sessionStorage.getItem("weather")) {
      let data = JSON.parse(sessionStorage.getItem("weather"));
      this.weather = data.data;
    } else {
      this.getCurrentWeather();
    }
    this.getWeatherForecast();
  }

  getSensor(id) {
    const message = {
      devicesId: id,
    };
    const uri = "devices/getSensorData";
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.sensorObj = data.data[0];
        sessionStorage.setItem(`sensorData${id}`, JSON.stringify(data.data[0]));
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
        console.log("weather");

        sessionStorage.setItem("weather", JSON.stringify(data));
        this.weather = data.data;
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
        console.log("deviceID");
        sessionStorage.setItem("deviceID", JSON.stringify(data));
        this.devices = data.data;
        for (let i = 0; i < data.data.length(); i++) {
          this.getSensor(data.data[i].id);
        }
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
        console.log("nutrients");
        sessionStorage.setItem("nutrients", JSON.stringify(data));
        this.nutrients = data.data;
        this.nutrient = this.nutrients[0];
      },
      (err: any) => {
        console.log(err);
      }
    );
  }

  ShowVegetInfo(type) {
    for (let i = 0; i < 10; i++) {
      this.veGetType[i] = false;
    }
    this.veGetType[type] = true;
    this.nutrient = this.nutrients[type];
  }
  getWeatherForecast() {
    const uri = "weather/forecast";
    this._dataService.post(uri, "").subscribe(
      (data: any) => {
        console.log(data);
        this.forecast = data.data.days;
        console.log(this.forecast[6]["10-13"].temperature.now.value);
        let a = this.forecast[6]["10-13"].time.day.date;
        this.forecastFlag = true;
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
}
