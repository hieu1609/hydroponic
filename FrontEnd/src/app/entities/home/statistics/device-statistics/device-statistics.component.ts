import { Component, OnInit, Input } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Observable, timer } from "rxjs";
@Component({
  selector: "app-device-statistics",
  templateUrl: "./device-statistics.component.html",
  styleUrls: ["./device-statistics.component.scss"]
})
export class DeviceStatisticsComponent implements OnInit {
  @Input() stat;
  constructor(private _dataService: DataService) {
    const source = timer(1000, 10000);
    const subscribe = source.subscribe(() => this.getSensor());
  }
  sensorObj: any = {};

  pumpStatusHTML: string = "OFF";
  pumpAutoStatusHTML: string = "OFF";
  ngOnInit() {
    this.getSensor();
    console.log(this.sensorObj);
  }

  getSensor() {
    console.log("test real-time");

    const message = {
      devicesId: this.stat.id
    };
    const uri = "devices/getSensorData";
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        console.log(data);
        this.sensorObj = data.data[0];
        if (this.sensorObj.pump === 0) {
          this.pumpStatusHTML = "OFF";
        } else this.pumpStatusHTML = "ON";
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
}
