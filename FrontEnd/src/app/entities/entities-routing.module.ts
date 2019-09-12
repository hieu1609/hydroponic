import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';


const routes: Routes = [
  {path:"",loadChildren:"./login/login.module#LoginModule"},
  { path: "home", loadChildren: "./home/home.module#HomeModule"},
  { path: "admin", loadChildren: "./admin/admin.module#AdminModule"}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EntitiesRoutingModule { }
