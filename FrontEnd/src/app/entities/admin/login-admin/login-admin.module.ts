import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { LoginAdminRoutingModule } from "./login-admin-routing.module";
import { LoginAdminComponent } from "./login-admin.component";
import { FormsModule } from "@angular/forms";

@NgModule({
  declarations: [LoginAdminComponent],
  exports: [LoginAdminComponent],
  imports: [CommonModule, LoginAdminRoutingModule, FormsModule]
})
export class LoginAdminModule {}
