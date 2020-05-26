import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { ListchartRoutingModule } from "./listchart-routing.module";
import { ListchartComponent } from "./listchart.component";
import { ChartModule } from "./chart/chart.module";
import { MatTabsModule } from "@angular/material/tabs";
@NgModule({
  declarations: [ListchartComponent],
  exports: [ListchartComponent, MatTabsModule],
  imports: [CommonModule, ListchartRoutingModule, ChartModule, MatTabsModule],
})
export class ListchartModule {}
