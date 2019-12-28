import { Component, OnInit, ViewChild } from "@angular/core";
import Swal from "sweetalert2";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import { NgForm } from "@angular/forms";

@Component({
  selector: "app-devices-management",
  templateUrl: "./devices-management.component.html",
  styleUrls: ["./devices-management.component.scss"]
})
export class DevicesManagementComponent implements OnInit {
  @ViewChild("formSignUp", { static: false }) formSignUp: NgForm;
  @ViewChild("formEdit", { static: false }) formEdit: NgForm;
  constructor(private _dataService: DataService, private router: Router) {}

  devicesList: any = [];
  idUserEdit;
  totalPage: any = [];
  currentPage;
  editflag: boolean = false;
  userList: any = [];
  ngOnInit() {
    this.getAllDevices(1);
  }
  getAllUsers(page) {
    const uri = `admin/getUserAdmin`;
    let message = {
      page
    };
    this.currentPage = page;
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.userList = data.data.data;
        if (this.userList.length === 0 && page !== 1) {
          this.getAllUsers(page - 1);
        }

        let i = 1;
        this.totalPage = [];
        while (i <= data.data.numPage) {
          this.totalPage.push(i);
          i++;
        }
      },
      (err: any) => {}
    );
  }
  getAllDevices(page) {
    const uri = `admin/getDevicesAdmin`;
    let message = {
      page
    };
    this.currentPage = page;
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.devicesList = data.data.data;
        if (this.devicesList.length === 0 && page !== 1) {
          this.getAllDevices(page - 1);
        }

        let i = 1;
        this.totalPage = [];
        while (i <= data.data.numPage) {
          this.totalPage.push(i);
          i++;
        }
      },
      (err: any) => {}
    );
  }
  DeleteDevice(item) {
    const uri = `admin/devices/${item.id}`;
    this._dataService.delete(uri).subscribe(
      (data: any) => {
        this.getAllDevices(this.currentPage);
        Swal.fire({
          icon: "success",
          title: "Delete Devices Successful",
          showConfirmButton: false,
          timer: 1500
        });
      },
      (err: any) => {}
    );
  }
  EditDevice(item) {
    this.editflag = true;
    this.idUserEdit = item.id;
    this.formEdit.setValue({
      deviceID: item.id,
      // username: item.username,
      // city: item.city,
      userId: item.user_id
    });
    this.editflag = true;
  }

  _handleOnSubmitEditForm() {
    const uri = `admin/devices/${this.idUserEdit}`;
    this._dataService.put(uri, this.formEdit.value).subscribe(
      (data: any) => {
        this.getAllDevices(this.currentPage);
        Swal.fire({
          icon: "success",
          title: "Update Successful",
          showConfirmButton: false,
          timer: 1500
        });
      },
      (err: any) => {}
    );
  }
  _handleOnSubmitAddForm() {
    const uri = "admin/addDevice";
    this._dataService.post(uri, this.formSignUp.value).subscribe(
      (data: any) => {
        this.getAllDevices(this.currentPage);
        Swal.fire({
          icon: "success",
          title: "Add Device Successful",
          showConfirmButton: false,
          timer: 1500
        });
        this.formSignUp.resetForm();
      },
      (err: any) => {}
    );
  }
}
