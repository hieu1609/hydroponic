import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { DevicesManagementRoutingModule } from "./devices-management-routing.module";
import { DevicesManagementComponent } from "./devices-management.component";
import { FormsModule } from "@angular/forms";

@NgModule({
  declarations: [DevicesManagementComponent],
  exports: [DevicesManagementComponent],
  imports: [CommonModule, DevicesManagementRoutingModule, FormsModule]
})
export class DevicesManagementModule {}
