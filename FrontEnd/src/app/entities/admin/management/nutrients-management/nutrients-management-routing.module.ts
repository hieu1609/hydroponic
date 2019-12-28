import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { NutrientsManagementComponent } from "./nutrients-management.component";

const routes: Routes = [
  {
    path: "",
    component: NutrientsManagementComponent,
    children: [{ path: "", component: NutrientsManagementComponent }]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class NutrientsManagementRoutingModule {}
