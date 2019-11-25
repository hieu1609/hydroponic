import { Component, OnInit, Input, ViewChild } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import { NgForm } from "@angular/forms";
import { formatDate } from "@angular/common";

@Component({
  selector: "app-device-control",
  templateUrl: "./device-control.component.html",
  styleUrls: ["./device-control.component.scss"]
})
export class DeviceControlComponent implements OnInit {
  @ViewChild("formPumpAuto", { static: false }) formPumpAuto: NgForm;
  @ViewChild("formPpmAuto", { static: false }) formPpmAuto: NgForm;
  @ViewChild("formPostNutrient", { static: false }) formPostNutrient: NgForm;

  @Input() device;
  constructor(private _dataService: DataService, private router: Router) {}
  statusPump: boolean = false;
  statusPumpAuto: boolean = false;
  statusPpmAuto: boolean = false;
  nutrients: any = [];
  today: number;
  ngOnInit() {
    this.getNutrients();
    // let dateFormat = require("dateformat");
    this.today = Date.now();
    let now = formatDate(this.today, "hh:mm:ss", "en-ES");
    console.log(now);
  }
  PumpOn() {
    const uri = "user/controlPump";
    this.PumpAutoOff();
    const message = {
      devicesId: this.device.id,
      message: "1"
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.statusPump = true;
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  PumpOff() {
    const uri = "user/controlPump";
    const message = {
      devicesId: this.device.id,
      message: "0"
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.statusPump = false;
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
        this.nutrients = data.data;
      },
      (err: any) => {
        console.log(err);
      }
    );
  }

  PumpAutoOn() {
    const uri = "user/pumpAutoOn";
    this.statusPumpAuto = true;
    const message = {
      devicesId: this.device.id,
      timeOn: this.formPumpAuto.value.timeOn,
      timeOff: this.formPumpAuto.value.timeOff
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {},
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
        this.statusPumpAuto = false;
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  PpmAutoOn() {
    const uri = "user/ppmAutoOn";
    this.statusPpmAuto = true;
    const message = {
      devicesId: this.device.id,
      nutrientId: this.formPpmAuto.value.option
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {},
      (err: any) => {
        console.log(err);
      }
    );
  }
  PpmAutoOff() {
    const uri = "user/ppmAutoOff";

    const message = {
      devicesId: this.device.id
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.statusPpmAuto = false;
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  PostNutrient() {
    const uri = "user/postNutrient";
    const message = {
      plantName: this.formPostNutrient.value.plantName,
      ppmMin: this.formPostNutrient.value.ppmMin,
      ppmMax: this.formPostNutrient.value.ppmMax
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.getNutrients();
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
}
