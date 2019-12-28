import { Component, OnInit, ViewChild } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import { NgForm } from "@angular/forms";
import Swal from "sweetalert2";

@Component({
  selector: "app-notification",
  templateUrl: "./notification.component.html",
  styleUrls: ["./notification.component.scss"]
})
export class NotificationComponent implements OnInit {
  constructor(private _dataService: DataService, private router: Router) {}
  @ViewChild("formAddNew", { static: false }) formAddNew: NgForm;
  @ViewChild("formEdit", { static: false }) formEdit: NgForm;
  notificationsList: any = [];
  notificationsListOverall: any = [];
  notificationsListUnread: any = [];
  notificationsListReceived: any = [];
  idFeedbackEdit;
  usernameRecei;
  flag = false;
  totalPage: any = [];
  currentPage;
  ngOnInit() {
    this.getNotifications(1);
  }
  displayOverallList() {
    this.notificationsList = this.notificationsListOverall;
  }
  displayUnreadList() {
    this.notificationsList = this.notificationsListUnread;
  }
  displayReceiveList() {
    this.notificationsList = this.notificationsListReceived;
  }
  getNotifications(page) {
    this.currentPage = page;
    const uri = `admin/getNotificationsAdmin`;
    let message = {
      page
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.notificationsList = data.data.data;
        this.notificationsListOverall = data.data.data;
        if (this.notificationsList.length === 0 && page !== 1) {
          this.getNotifications(page - 1);
        }
        this.notificationsListUnread = [];
        this.notificationsListReceived = [];
        for (let item of this.notificationsList) {
          if (item.seen == 0) {
            this.notificationsListUnread.push(item);
          } else {
            this.notificationsListReceived.push(item);
          }
        }
        console.log(data.data.numPage);
        let i = 1;
        this.totalPage = [];
        while (i <= data.data.numPage) {
          this.totalPage.push(i);
          i++;
        }
        console.log(this.totalPage);
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  ShowFeedbackDetail(item) {
    let objFeedBack = {
      userIdSend: item.user_id_send,
      userIdReceive: 1,
      notificationTitle: item.title,
      notificationContent: item.content,
      seen: true
    };
    this.usernameRecei = item.name;
    console.log(objFeedBack);
    this.idFeedbackEdit = item.id;
    console.log(this.formEdit.value);
    const uri = `admin/notification/${this.idFeedbackEdit}`;
    this._dataService.put(uri, objFeedBack).subscribe(
      (data: any) => {
        this.getNotifications(this.currentPage);
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  SendNotifiToUser() {
    this.flag = true;
  }
  SendNotifiToAllUser() {
    this.flag = false;
  }
  EditNotifications(item) {
    console.log(item);
    this.flag = true;
    this.idFeedbackEdit = item.id;
    this.formEdit.setValue({
      userid: item.user_id_receive,
      title: item.title,
      content: item.content,
      name: item.username
    });

    console.log(this.formEdit.value);
  }

  _handleOnSubmitEditForm() {
    let objNotification = {
      userIdSend: 1,
      userIdReceive: this.formEdit.value.userid,
      notificationTitle: this.formEdit.value.title,
      notificationContent: this.formEdit.value.content,
      seen: true
    };

    const uri = `admin/notification/${this.idFeedbackEdit}`;
    this._dataService.put(uri, objNotification).subscribe(
      (data: any) => {
        this.getNotifications(this.currentPage);
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  DeleteNotifications(item) {
    this.idFeedbackEdit = item.id;
    const uri = `admin/notification/${this.idFeedbackEdit}`;
    this._dataService.delete(uri).subscribe(
      (data: any) => {
        this.getNotifications(this.currentPage);
        Swal.fire({
          icon: "success",
          title: "Delete successful!",
          showConfirmButton: false,
          timer: 1500
        });
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  _handleOnSubmitAddForm() {
    console.log(this.formAddNew.value);
    let uri;
    if (this.flag) {
      uri = "admin/sendNotification";
    } else {
      uri = "admin/sendNotificationForAllUsers";
    }
    console.log(uri);

    this._dataService.post(uri, this.formAddNew.value).subscribe(
      (data: any) => {
        this.getNotifications(this.currentPage);
        this.formAddNew.resetForm();
        Swal.fire({
          icon: "success",
          title: "Send notification successful!",
          showConfirmButton: false,
          timer: 1500
        });
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
}
