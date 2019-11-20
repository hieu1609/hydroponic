import { Component, OnInit, Input } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";

@Component({
  selector: "app-device-control",
  templateUrl: "./device-control.component.html",
  styleUrls: ["./device-control.component.scss"]
})
export class DeviceControlComponent implements OnInit {
  @Input() device;
  constructor(private _dataService: DataService, private router: Router) {}
  statusPump: boolean = false;
  statusPumpAuto: boolean = false;
  ngOnInit() {}
  PumpOn() {
    this.statusPump = true;
    const uri = "user/controlPump";
    const message = {
      devicesId: this.device.id,
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
    const uri = "user/controlPump";
    const message = {
      devicesId: this.device.id,
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
    const uri = "user/pumpAutoOn";
    const message = {
      devicesId: this.device.id,
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
    const uri = "user/pumpAutoOff";
    const message = {
      devicesId: this.device.id
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
}
