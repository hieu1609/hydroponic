import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { FormRoutingModule } from "./form-routing.module";
import { FormComponent } from "./form.component";
import { LoginComponent } from "./login/login.component";

@NgModule({
  declarations: [FormComponent, LoginComponent],
  imports: [CommonModule, FormRoutingModule, FormsModule]
})
export class FormModule {}
