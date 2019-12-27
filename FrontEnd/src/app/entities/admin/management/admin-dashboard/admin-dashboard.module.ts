import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { AdminDashboardRoutingModule } from "./admin-dashboard-routing.module";
import { AdminDashboardComponent } from "./admin-dashboard.component";

@NgModule({
  declarations: [AdminDashboardComponent],
  exports: [AdminDashboardComponent],
  imports: [CommonModule, AdminDashboardRoutingModule]
})
export class AdminDashboardModule {}
