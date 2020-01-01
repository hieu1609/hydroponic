import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { UserManagementRoutingModule } from "./user-management-routing.module";
import { UserManagementComponent } from "./user-management.component";

import { FormsModule } from "@angular/forms";

@NgModule({
  declarations: [UserManagementComponent],
  exports: [UserManagementComponent],
  imports: [CommonModule, UserManagementRoutingModule, FormsModule]
})
export class UserManagementModule {}
