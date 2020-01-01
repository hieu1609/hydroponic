import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { DevicesManagementComponent } from "./devices-management.component";

const routes: Routes = [
  {
    path: "",
    component: DevicesManagementComponent,
    children: [{ path: "", component: DevicesManagementComponent }]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DevicesManagementRoutingModule {}
