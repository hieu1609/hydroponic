import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { AuthGuard } from "./../common/guards/auth.guard";

const routes: Routes = [
  { path: "", loadChildren: "./form/form.module#FormModule" },
  // {
  //   path: "",
  //   loadChildren: "./home/home.module#HomeModule",
  //   canActivate: [AuthGuard]
  // },
  {
    path: "",
    loadChildren: "./home/home.module#HomeModule",
    canActivate: [AuthGuard]
  },
  { path: "admin", loadChildren: "./admin/admin.module#AdminModule" }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EntitiesRoutingModule {}
