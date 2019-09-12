import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeModule } from './entities/home/home.module';
import { AdminModule } from './entities/admin/admin.module';
import { EntitiesModule } from './entities/entities.module';

@NgModule({
  declarations: [
    AppComponent,
   
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HomeModule,
    AdminModule,
    EntitiesModule,
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
