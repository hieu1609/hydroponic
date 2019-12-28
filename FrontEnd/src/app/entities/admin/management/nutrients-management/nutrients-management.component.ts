import { Component, OnInit, ViewChild } from "@angular/core";
import { NgForm } from "@angular/forms";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import Swal from "sweetalert2";

@Component({
  selector: "app-nutrients-management",
  templateUrl: "./nutrients-management.component.html",
  styleUrls: ["./nutrients-management.component.scss"]
})
export class NutrientsManagementComponent implements OnInit {
  @ViewChild("formSignUp", { static: false }) formSignUp: NgForm;
  @ViewChild("formEdit", { static: false }) formEdit: NgForm;
  constructor(private _dataService: DataService, private router: Router) {}

  nutrientsList: any = [];
  idUserEdit;
  totalPage: any = [];
  currentPage;
  editflag: boolean = false;
  ngOnInit() {
    this.getListNutrients(1);
  }
  getListNutrients(page) {
    const uri = `admin/getNutrientsAdmin`;
    let message = {
      page
    };
    this.currentPage = page;
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.nutrientsList = data.data.data;
        if (this.nutrientsList.length === 0 && page !== 1) {
          this.getListNutrients(page - 1);
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
  DeleteNutrient(item) {
    const uri = `admin/nutrient/${item.id}`;
    this._dataService.delete(uri).subscribe(
      (data: any) => {
        this.getListNutrients(this.currentPage);
        Swal.fire({
          icon: "success",
          title: "Delete Nutrient Successful",
          showConfirmButton: false,
          timer: 1500
        });
      },
      (err: any) => {}
    );
  }
  EditNutrient(item) {
    this.editflag = true;
    this.idUserEdit = item.id;
    this.formEdit.setValue({
      plantName: item.plant_name,
      userId: item.user_id,
      ppmMin: item.ppm_min,
      ppmMax: item.ppm_max
    });
    this.editflag = true;
  }

  _handleOnSubmitEditForm() {
    const uri = `admin/nutrient/${this.idUserEdit}`;
    this._dataService.put(uri, this.formEdit.value).subscribe(
      (data: any) => {
        this.getListNutrients(this.currentPage);
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
    const uri = "admin/addNutrient";
    this._dataService.post(uri, this.formSignUp.value).subscribe(
      (data: any) => {
        this.getListNutrients(this.currentPage);
        Swal.fire({
          icon: "success",
          title: "Add Nutrient Successful",
          showConfirmButton: false,
          timer: 1500
        });
        this.formSignUp.resetForm();
      },
      (err: any) => {}
    );
  }
}
