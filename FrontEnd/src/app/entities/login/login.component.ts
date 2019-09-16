import { Component, OnInit } from "@angular/core";

@Component({
  selector: "app-login",
  templateUrl: "./login.component.html",
  styleUrls: ["./login.component.scss"]
})
export class LoginComponent implements OnInit {
  constructor() {}
  signIn: boolean = true;
  SignIn() {
    this.signIn = false;
  }
  SignUp() {
    this.signIn = true;
  }
  ngOnInit() {}
}
