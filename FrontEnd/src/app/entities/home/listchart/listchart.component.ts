import { Component, OnInit, ViewChild } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import { Observable, timer } from "rxjs";
import { DomSanitizer } from "@angular/platform-browser";
import { NgForm } from "@angular/forms";
@Component({
  selector: "app-listchart",
  templateUrl: "./listchart.component.html",
  styleUrls: ["./listchart.component.scss"]
})
export class ListchartComponent implements OnInit {
  constructor(
    private _dataService: DataService,
    private router: Router,
    private sanitizer: DomSanitizer
  ) {}
  temperature: string = "temperature";
  humidity: string = "humidity";
  light: string = "light";
  ec: string = "EC";
  ppm: string = "PPM";

  @ViewChild("f", { static: false }) f: NgForm;

  devices: any = [];
  typeChart: any = [];
  id: any;
  ngOnInit() {
    this.getDeviceID();
  }

  // showChart() {
  //   console.log(this.f.value);
  // }
  getDeviceID() {
    const uri = "devices/getDeviceIdForUser";
    this._dataService.get(uri).subscribe(
      (data: any) => {
        this.devices = data.data;
        this.id = this.devices[0].id;
        // alert("Đăng nhập thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
}
