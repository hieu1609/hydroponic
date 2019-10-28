import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { HomeRoutingModule } from "./home-routing.module";
import { HomeComponent } from "./home.component";
import { HomepageModule } from "./homepage/homepage.module";
import { ControlModule } from "./control/control.module";
import { StatisticsModule } from "./statistics/statistics.module";

@NgModule({
  declarations: [HomeComponent],
  exports: [HomeComponent],
  imports: [
    CommonModule,
    HomeRoutingModule,
    HomepageModule,
    ControlModule,
    StatisticsModule
  ]
})
export class HomeModule {}
