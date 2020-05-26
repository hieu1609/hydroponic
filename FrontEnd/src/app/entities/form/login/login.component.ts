import { Component, OnInit, ViewChild } from "@angular/core";
import { NgForm } from "@angular/forms";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import Swal from "sweetalert2";

@Component({
  selector: "app-login",
  templateUrl: "./login.component.html",
  styleUrls: ["./login.component.scss"]
})
export class LoginComponent implements OnInit {
  @ViewChild("formSignIn", { static: false }) formSignIn: NgForm;
  @ViewChild("formSignUp", { static: false }) formSignUp: NgForm;
  constructor(private _dataService: DataService, private router: Router) {}
  signIn: boolean = false;
  SignIn() {
    this.signIn = false;
  }
  SignUp() {
    this.signIn = true;
  }
  _handleOnSubmit() {
    const uri = "auth/login";
    if (this.formSignIn.valid) console.log(this.formSignIn.value);

    this._dataService.post(uri, this.formSignIn.value).subscribe(
      (data: any) => {
        Swal.fire({
          icon: "success",
          title: "Successul",
          text: "Đăng nhập thành công",
          showConfirmButton: false,
          timer: 2500
        });
        localStorage.setItem("user", JSON.stringify(data));
        this.router.navigate(["home"]);
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  _handleOnSubmitSignUp() {
    const uri = "auth/register";
    this._dataService.post(uri, this.formSignUp.value).subscribe(
      (data: any) => {
        Swal.fire({
          icon: "success",
          title: "Successul",
          text: "Đăng ký thành công",
          showConfirmButton: false,
          timer: 2500
        });
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  ngOnInit() {}
}
