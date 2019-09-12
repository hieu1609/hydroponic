import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EntitiesRoutingModule } from './entities-routing.module';
import { EntitiesComponent } from './entities.component';
import { LoginModule } from './login/login.module';


@NgModule({
  declarations: [EntitiesComponent],
  exports:[EntitiesComponent],
  imports: [
    CommonModule,
    EntitiesRoutingModule,
    LoginModule,
  ]
})
export class EntitiesModule { }
