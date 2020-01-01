import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { LoginAdminComponent } from "./login-admin.component";

const routes: Routes = [
  {
    path: "",
    component: LoginAdminComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LoginAdminRoutingModule {}
