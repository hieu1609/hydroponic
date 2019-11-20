import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { EntitiesRoutingModule } from "./entities-routing.module";
import { EntitiesComponent } from "./entities.component";
import { HomeModule } from "./home/home.module";
import { ChartModule } from "./home/chart/chart.module";

@NgModule({
  declarations: [EntitiesComponent],
  exports: [EntitiesComponent],
  imports: [CommonModule, EntitiesRoutingModule, HomeModule, ChartModule]
})
export class EntitiesModule {}
