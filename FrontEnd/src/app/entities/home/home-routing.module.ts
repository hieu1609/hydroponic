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
        path: "chart",
        loadChildren: "./listchart/listchart.module#ListchartModule"
      },
      {
        path: "contact",
        loadChildren: "./contact/contact.module#ContactModule"
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HomeRoutingModule {}
