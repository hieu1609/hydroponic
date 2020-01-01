import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { ListchartComponent } from "./listchart.component";

const routes: Routes = [
  {
    path: "",
    component: ListchartComponent,
    children: [
      {
        path: "",
        loadChildren: "./chart/chart.module#ChartModule"
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ListchartRoutingModule {}
