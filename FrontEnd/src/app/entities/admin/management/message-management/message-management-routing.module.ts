import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { MessageManagementComponent } from "./message-management.component";
import { NotificationComponent } from "./notification/notification.component";
import { FeedbackComponent } from "./feedback/feedback.component";

const routes: Routes = [
  {
    path: "",
    component: MessageManagementComponent,
    children: [
      { path: "notification", component: NotificationComponent },
      { path: "feedback", component: FeedbackComponent }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MessageManagementRoutingModule {}
