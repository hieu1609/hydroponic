import { Component, OnInit, Input, ViewChild } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import { NgForm } from "@angular/forms";
import { formatDate } from "@angular/common";
import Swal from "sweetalert2";

@Component({
  selector: "app-device-control",
  templateUrl: "./device-control.component.html",
  styleUrls: ["./device-control.component.scss"],
})
export class DeviceControlComponent implements OnInit {
  @ViewChild("formPumpAuto", { static: false }) formPumpAuto: NgForm;
  @ViewChild("formPpmAuto", { static: false }) formPpmAuto: NgForm;
  @ViewChild("formPostNutrient", { static: false }) formPostNutrient: NgForm;

  @Input() device;
  constructor(private _dataService: DataService, private router: Router) {}
  statusPump: boolean = false;
  statusWaterIn: boolean = true;
  statusWaterOut: boolean = false;
  statusMix: boolean = false;
  statusAddNutrition: boolean = false;
  statusPumpAuto: boolean = false;
  statusPpmAuto: boolean = false;
  nutrients: any = [];
  today: number;
  ngOnInit() {
    if (sessionStorage.getItem("nutrients")) {
      let data = JSON.parse(sessionStorage.getItem("nutrients"));
      this.nutrients = data.data;
    } else {
      this.getNutrients();
    }
    if (sessionStorage.getItem("sensorData")) {
      let data = JSON.parse(sessionStorage.getItem("sensorData"));
      this.statusWaterIn = data.water_in;
      this.statusWaterOut = data.water_out;
      this.statusMix = data.mix;

      if (data.pump === 0) {
        this.statusPump = false;
      } else {
        this.statusPump = true;
      }
    }
  }
  PumpOn() {
    const uri = "user/controlPump";
    this.PumpAutoOff();
    const message = {
      devicesId: this.device.id,
      message: "1",
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
      message: "0",
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

    if (
      this.formPumpAuto.value.timeOn !== "" &&
      this.formPumpAuto.value.timeOff !== ""
    ) {
      this.statusPumpAuto = true;
      const message = {
        devicesId: this.device.id,
        timeOn: this.formPumpAuto.value.timeOn,
        timeOff: this.formPumpAuto.value.timeOff,
      };
      this._dataService.post(uri, message).subscribe(
        (data: any) => {},
        (err: any) => {
          console.log(err);
        }
      );
    } else {
      Swal.fire({
        icon: "error",
        title: "Time on, timeoff is required",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  }

  PumpAutoOff() {
    const uri = "user/pumpAutoOff";

    const message = {
      devicesId: this.device.id,
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
      nutrientId: this.formPpmAuto.value.option,
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
      devicesId: this.device.id,
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
      ppmMax: this.formPostNutrient.value.ppmMax,
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
  waterInFunc() {
    this.controlWaterIn();
    this.checkWaterIn();
  }
  waterOutFunc() {
    this.controlWaterOut();
    this.checkWaterOut();
  }
  controlWaterIn() {
    this.statusWaterIn = !this.statusWaterIn;
    console.log(this.statusWaterIn.toString());

    const uri = "user/controlWaterIn";
    let status;
    if (this.statusWaterIn) {
      status = "1";
    } else {
      status = "0";
    }
    const message = {
      devicesId: this.device.id,
      message: status,
    };
    console.log("water in");

    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        console.log("success");

        // this.checkWaterIn();
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  checkWaterIn() {
    const uri = "user/checkWaterIn";

    const message = {
      devicesId: this.device.id,
    };
    console.log(message);

    this._dataService.post(uri, message).subscribe(
      (data: any) => {},
      (err: any) => {
        console.log(err);
      }
    );
  }
  checkWaterOut() {
    const uri = "user/checkWaterOut";

    const message = {
      devicesId: this.device.id,
    };
    console.log(message);

    this._dataService.post(uri, message).subscribe(
      (data: any) => {},
      (err: any) => {
        console.log(err);
      }
    );
  }
  controlWaterOut() {
    this.statusWaterOut = !this.statusWaterOut;

    const uri = "user/controlWaterOut";
    let status;
    if (this.statusWaterOut) {
      status = "1";
    } else {
      status = "0";
    }
    const message = {
      devicesId: this.device.id,
      message: status,
    };
    console.log("water out");

    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        // this.checkWaterOut();
      },
      (err: any) => {
        console.log(err);
      }
    );
  }

  controlMix() {
    this.statusMix = !this.statusMix;
    console.log(this.statusMix.toString());
    let status;
    if (this.statusMix) {
      status = "1";
    } else {
      status = "0";
    }
    const uri = "user/controlMix";

    const message = {
      devicesId: this.device.id,
      message: status,
    };
    console.log(message);

    this._dataService.post(uri, message).subscribe(
      (data: any) => {},
      (err: any) => {
        console.log(err);
      }
    );
  }

  addNutrition() {
    const uri = "user/controlPpm";
    this.statusAddNutrition = true;
    setTimeout(() => {
      this.statusAddNutrition = false;
    }, 2000);

    const message = {
      devicesId: this.device.id,
    };
    console.log(message);

    this._dataService.post(uri, message).subscribe(
      (data: any) => {},
      (err: any) => {
        console.log(err);
      }
    );
  }
}
