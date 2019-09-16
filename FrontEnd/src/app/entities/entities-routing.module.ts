import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { AuthGuard } from "./../common/guards/auth.guard";

const routes: Routes = [
  { path: "login", loadChildren: "./login/login.module#LoginModule" },
  {
    path: "",
    loadChildren: "./home/home.module#HomeModule",
    canActivate: [AuthGuard]
  },
  {
    path: "home",
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
