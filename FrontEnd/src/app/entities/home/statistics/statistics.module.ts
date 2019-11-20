import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { StatisticsRoutingModule } from "./statistics-routing.module";
import { StatisticsComponent } from "./statistics.component";

import { DeviceStatisticsComponent } from "./device-statistics/device-statistics.component";
import { DeviceControlComponent } from './device-control/device-control.component';

@NgModule({
  declarations: [StatisticsComponent, DeviceStatisticsComponent, DeviceControlComponent],
  imports: [CommonModule, StatisticsRoutingModule]
})
export class StatisticsModule {}
