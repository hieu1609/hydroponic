import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
@Component({
  selector: "app-homepage",
  templateUrl: "./homepage.component.html",
  styleUrls: ["./homepage.component.scss"]
})
export class HomepageComponent implements OnInit {
  constructor(private _dataService: DataService, private router: Router) {}

  ngOnInit() {}
  logOut() {
    const uri = "auth/logout";
    this._dataService.post(uri).subscribe(
      (data: any) => {
        console.log(data);
        alert("Đăng xuất thành công !");
        // localStorage.setItem("user", JSON.stringify(data));
        this.router.navigate([""]);
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
}
