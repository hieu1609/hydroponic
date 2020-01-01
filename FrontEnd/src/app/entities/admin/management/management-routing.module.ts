import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { ManagementComponent } from "./management.component";

const routes: Routes = [
  {
    path: "",
    component: ManagementComponent,
    children: [
      {
        path: "dashboard",
        loadChildren:
          "./admin-dashboard/admin-dashboard.module#AdminDashboardModule"
      },
      {
        path: "users-management",
        loadChildren:
          "./user-management/user-management.module#UserManagementModule"
      },
      {
        path: "devices-management",
        loadChildren:
          "./devices-management/devices-management.module#DevicesManagementModule"
      },
      {
        path: "nutrients-management",
        loadChildren:
          "./nutrients-management/nutrients-management.module#NutrientsManagementModule"
      },
      {
        path: "message-management",
        loadChildren:
          "./message-management/message-management.module#MessageManagementModule"
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ManagementRoutingModule {}
