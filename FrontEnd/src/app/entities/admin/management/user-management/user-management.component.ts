import { Component, OnInit, ViewChild } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import { NgForm } from "@angular/forms";
import Swal from "sweetalert2";

@Component({
  selector: "app-user-management",
  templateUrl: "./user-management.component.html",
  styleUrls: ["./user-management.component.scss"]
})
export class UserManagementComponent implements OnInit {
  @ViewChild("formSignUp", { static: false }) formSignUp: NgForm;
  @ViewChild("formEdit", { static: false }) formEdit: NgForm;
  constructor(private _dataService: DataService, private router: Router) {}

  userList: any = [];
  idUserEdit;
  totalPage: any = [];
  currentPage;
  editflag: boolean = false;
  ngOnInit() {
    this.getAllUsers(1);
  }
  getAllUsers(page) {
    const uri = `admin/all-user?page=${page}`;

    this.currentPage = page;
    this._dataService.get(uri).subscribe(
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
  DeleteUser(item) {
    const uri = `admin/${item.id}`;
    this._dataService.delete(uri).subscribe(
      (data: any) => {
        this.getAllUsers(this.currentPage);
        Swal.fire({
          icon: "success",
          title: "Delete User Successful",
          showConfirmButton: false,
          timer: 1500
        });
      },
      (err: any) => {}
    );
  }
  EditUser(item) {
    this.editflag = true;
    this.idUserEdit = item.id;
    this.formEdit.setValue({
      email: item.email,
      username: item.username,
      city: item.city,
      admin: item.admin
    });
    this.editflag = true;
  }

  _handleOnSubmitEditForm() {
    const uri = `admin/${this.idUserEdit}`;
    this._dataService.put(uri, this.formEdit.value).subscribe(
      (data: any) => {
        this.getAllUsers(this.currentPage);
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
    const uri = "admin/addUser";
    this._dataService.post(uri, this.formSignUp.value).subscribe(
      (data: any) => {
        this.getAllUsers(this.currentPage);
        Swal.fire({
          icon: "success",
          title: "Add User Successful",
          showConfirmButton: false,
          timer: 1500
        });
        this.formSignUp.resetForm();
      },
      (err: any) => {}
    );
  }
}
