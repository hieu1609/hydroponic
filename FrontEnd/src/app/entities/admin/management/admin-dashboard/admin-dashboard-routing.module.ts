import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { AdminDashboardComponent } from "./admin-dashboard.component";

const routes: Routes = [
  {
    path: "",
    component: AdminDashboardComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminDashboardRoutingModule {}
