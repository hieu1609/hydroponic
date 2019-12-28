import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { NutrientsManagementRoutingModule } from "./nutrients-management-routing.module";
import { NutrientsManagementComponent } from "./nutrients-management.component";
import { FormsModule } from "@angular/forms";

@NgModule({
  declarations: [NutrientsManagementComponent],
  exports: [NutrientsManagementComponent],
  imports: [CommonModule, NutrientsManagementRoutingModule, FormsModule]
})
export class NutrientsManagementModule {}
