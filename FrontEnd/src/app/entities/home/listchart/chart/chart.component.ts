import { Component, OnInit, Input } from "@angular/core";
import * as CanvasJS from "./../../../../../assets/canvasjs.min.js";
import * as $ from "jquery";
import { DataService } from "src/app/shared/data.service.js";
import { Router } from "@angular/router";
import { timer, SubscriptionLike } from "rxjs";
import { takeWhile } from "rxjs/operators";
import { ChartDataSets } from "chart.js";
import { Label, Color } from "ng2-charts";

@Component({
  selector: "app-chart",
  templateUrl: "./chart.component.html",
  styleUrls: ["./chart.component.scss"]
})
export class ChartComponent implements OnInit {
  source: any;
  subscription: SubscriptionLike;
  constructor(private _dataService: DataService, private router: Router) {
    this.source = timer(5000, 5000);

    this.subscription = this.source.subscribe(() => this.getSensor(this.id));
  }
  @Input() index;
  @Input() id;
  ngOnDestroy() {
    this.subscription.unsubscribe();
  }
  dataArrayTemp: any = [];
  dataLabelArrayTemp: any = [];

  dataArrayHum: any = [];
  dataLabelArrayHum: any = [];

  dataArrayLight: any = [];
  dataLabelArrayLight: any = [];

  dataArrayEC: any = [];
  dataLabelArrayEC: any = [];

  dataArrayPPM: any = [];
  dataLabelArrayPPM: any = [];

  i: number = 1;

  tem: boolean;
  hum: boolean;
  light: boolean;
  ec: boolean;
  ppm: boolean;
  ngOnInit() {
    this.getSensorDataChart(this.id);
  }
  getSensorDataChart(deviceID) {
    const message = {
      devicesId: deviceID
    };
    const uri = "devices/getSensorDataChart";
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        for (let index = 0; index < data.data.length; index++) {
          this.dataArrayTemp[index] = data.data[index].temperature;
          this.dataArrayHum[index] = data.data[index].humidity;
          this.dataArrayLight[index] = data.data[index].light;
          this.dataArrayEC[index] = data.data[index].EC;
          this.dataArrayPPM[index] = data.data[index].PPM;

          let time: string = data.data[index].updated_at.split(" ");
          this.dataLabelArrayTemp[index] = time[1];
          this.dataLabelArrayHum[index] = time[1];
          this.dataLabelArrayLight[index] = time[1];
          this.dataLabelArrayEC[index] = time[1];
          this.dataLabelArrayPPM[index] = time[1];
        }
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  getSensor(id) {
    const message = {
      devicesId: id
    };
    const uri = "devices/getSensorData";
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        console.log(data.data[0]);

        let time: string = data.data[0].updated_at.split(" ");
        if (
          time[1] !==
          this.dataLabelArrayTemp[this.dataLabelArrayTemp.length - 1]
        ) {
          this.dataArrayTemp.splice(0, 1);
          this.dataLabelArrayTemp.splice(0, 1);
          this.dataArrayTemp.push(data.data[0].temperature);
          this.dataLabelArrayTemp.push(time[1]);

          this.dataArrayHum.splice(0, 1);
          this.dataLabelArrayHum.splice(0, 1);
          this.dataArrayHum.push(data.data[0].humidity);
          this.dataLabelArrayHum.push(time[1]);

          this.dataArrayLight.splice(0, 1);
          this.dataLabelArrayLight.splice(0, 1);
          this.dataArrayLight.push(data.data[0].light);
          this.dataLabelArrayLight.push(time[1]);

          this.dataArrayEC.splice(0, 1);
          this.dataLabelArrayEC.splice(0, 1);
          this.dataArrayEC.push(data.data[0].EC);
          this.dataLabelArrayEC.push(time[1]);

          this.dataArrayPPM.splice(0, 1);
          this.dataLabelArrayPPM.splice(0, 1);
          this.dataArrayPPM.push(data.data[0].PPM);
          this.dataLabelArrayPPM.push(time[1]);
        }
        //this.i = -this.i;
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  // temperature
  lineChartData: ChartDataSets[] = [
    { data: this.dataArrayTemp, label: "Temperature" }
  ];
  lineChartLabels: Label[] = this.dataLabelArrayTemp;

  lineChartData1: ChartDataSets[] = [
    { data: this.dataArrayHum, label: "Humidity" }
  ];
  lineChartLabels1: Label[] = this.dataLabelArrayHum;

  lineChartData2: ChartDataSets[] = [
    { data: this.dataArrayLight, label: "Light" }
  ];
  lineChartLabels2: Label[] = this.dataLabelArrayLight;

  lineChartData3: ChartDataSets[] = [{ data: this.dataArrayEC, label: "EC" }];
  lineChartLabels3: Label[] = this.dataLabelArrayEC;

  lineChartData4: ChartDataSets[] = [{ data: this.dataArrayPPM, label: "TDM" }];
  lineChartLabels4: Label[] = this.dataLabelArrayPPM;

  lineChartOptions = {
    responsive: true
  };

  lineChartColors: Color[] = [
    {
      borderColor: "red",
      backgroundColor: "rgba(255, 60, 0, 0.931)"
    }
  ];
  lineChartColors1: Color[] = [
    {
      borderColor: "blue",
      backgroundColor: "rgba(0, 225, 255, 0.931)"
    }
  ];
  lineChartColors2: Color[] = [
    {
      borderColor: "rgb(255, 157, 0)",
      backgroundColor: "rgba(255, 238, 0, 0.828)"
    }
  ];
  lineChartColors3: Color[] = [
    {
      borderColor: "green",
      backgroundColor: "rgba(0, 255, 13, 0.931)"
    }
  ];
  lineChartColors4: Color[] = [
    {
      borderColor: "gray",
      backgroundColor: "rgba(124, 124, 124, 0.828)"
    }
  ];

  lineChartLegend = true;
  lineChartPlugins = [];
  lineChartType = "line";
}
