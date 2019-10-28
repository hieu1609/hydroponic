import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { ControlComponent } from "./control.component";

const routes: Routes = [{ path: "", component: ControlComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ControlRoutingModule {}
