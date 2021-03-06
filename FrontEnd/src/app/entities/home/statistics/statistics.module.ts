import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { StatisticsRoutingModule } from "./statistics-routing.module";
import { StatisticsComponent } from "./statistics.component";

import { DeviceStatisticsComponent } from "./device-statistics/device-statistics.component";
import { DeviceControlComponent } from "./device-control/device-control.component";
import { FormsModule } from "@angular/forms";
import { MatTabsModule } from "@angular/material/tabs";

@NgModule({
  declarations: [
    StatisticsComponent,
    DeviceStatisticsComponent,
    DeviceControlComponent,
  ],
  imports: [CommonModule, StatisticsRoutingModule, FormsModule, MatTabsModule],
  exports: [MatTabsModule],
})
export class StatisticsModule {}
