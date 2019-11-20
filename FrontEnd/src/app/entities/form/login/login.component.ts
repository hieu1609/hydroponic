import { Component, OnInit, ViewChild } from "@angular/core";
import { NgForm } from "@angular/forms";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";

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
        console.log(data);
        alert("Đăng nhập thành công !");
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
    if (this.formSignUp.valid) console.log(this.formSignUp);
    this._dataService.post(uri, this.formSignUp.value).subscribe(
      (data: any) => {
        //console.log(data)
        alert("Đăng ký thành công !");
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  ngOnInit() {}
}
