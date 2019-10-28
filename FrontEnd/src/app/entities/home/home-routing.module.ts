import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { HomeComponent } from "./home.component";

const routes: Routes = [
  {
    path: "",
    component: HomeComponent,
    children: [
      {
        path: "home",
        loadChildren: "./homepage/homepage.module#HomepageModule"
      },
      {
        path: "stat",
        loadChildren: "./statistics/statistics.module#StatisticsModule"
      },
      {
        path: "control",
        loadChildren: "./control/control.module#ControlModule"
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HomeRoutingModule {}
