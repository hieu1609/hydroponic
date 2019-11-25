import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { ListchartRoutingModule } from "./listchart-routing.module";
import { ListchartComponent } from "./listchart.component";
import { ChartModule } from "./chart/chart.module";

@NgModule({
  declarations: [ListchartComponent],
  exports: [ListchartComponent],
  imports: [CommonModule, ListchartRoutingModule, ChartModule]
})
export class ListchartModule {}
