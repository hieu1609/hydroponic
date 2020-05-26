import { Component, OnInit, ViewChild } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import { NgForm } from "@angular/forms";
import Swal from "sweetalert2";

@Component({
  selector: "app-contact",
  templateUrl: "./contact.component.html",
  styleUrls: ["./contact.component.scss"]
})
export class ContactComponent implements OnInit {
  constructor(private _dataService: DataService, private router: Router) {}
  @ViewChild("formDetail", { static: false }) formDetail: NgForm;
  @ViewChild("formEdit", { static: false }) formEdit: NgForm;
  @ViewChild("formEdit1", { static: false }) formEdit1: NgForm;
  feedbacksList: any = [];
  feedbacksListOverall: any = [];
  feedbacksListUnread: any = [];
  feedbacksListReceived: any = [];
  idFeedbackEdit;
  totalPage: any = [];
  currentPage;
  ngOnInit() {
    this.getAllNotification();
  }
  displayOverallList() {
    this.feedbacksList = this.feedbacksListOverall;
  }
  displayUnreadList() {
    this.feedbacksList = this.feedbacksListUnread;
  }
  displayReceiveList() {
    this.feedbacksList = this.feedbacksListReceived;
  }
  getAllNotification() {
    const uri = `user/getNotifications`;

    this._dataService.get(uri).subscribe(
      (data: any) => {
        this.feedbacksList = data.data;
        this.feedbacksListOverall = data.data;

        this.feedbacksListUnread = [];
        this.feedbacksListReceived = [];
        for (let item of this.feedbacksList) {
          if (item.seen == 0) {
            this.feedbacksListUnread.push(item);
          } else {
            this.feedbacksListReceived.push(item);
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
  Seen(id) {
    const uri = `user/seenNotification`;
    let message = {
      notificationId: id
    };
    this._dataService.put(uri, message).subscribe(
      (data: any) => {
        this.getAllNotification();
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  ShowFeedbackDetail(item) {
    this.formDetail.setValue({
      userid: item.user_id_send,
      title: item.title,
      content: item.content
    });
    let objFeedBack = {
      userIdSend: item.user_id_send,
      userIdReceive: 1,
      notificationTitle: item.title,
      notificationContent: item.content,
      seen: true
    };

    this.idFeedbackEdit = item.id;
    this.Seen(item.id);
  }

  SendFeedback(item) {
    console.log(item);

    this.idFeedbackEdit = item.id;
    this.formEdit.setValue({
      userid: item.user_id_send,
      title: item.title,
      content: null
    });

    console.log(this.formEdit.value);
  }
  ReplyNotification(item) {
    console.log(item);

    this.idFeedbackEdit = item.id;
    this.formEdit.setValue({
      userid: item.user_id_send,
      title: item.title,
      content: null
    });
    this.ShowFeedbackDetail(item);
    console.log(this.formEdit.value);
  }
  _handleOnSubmitEditForm() {
    let objReply = {
      feedbackTitle: this.formEdit.value.title,
      feedbackContent: this.formEdit.value.content
    };
    console.log(objReply);

    console.log(this.formEdit.value);
    const uri = `user/postFeedback`;
    this._dataService.post(uri, objReply).subscribe(
      (data: any) => {
        this.getAllNotification();
        Swal.fire({
          icon: "success",
          title: "Send Feedback successful!",
          showConfirmButton: false,
          timer: 1500
        });
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  _handleOnSubmitEditForm1() {
    let objReply = {
      feedbackTitle: this.formEdit1.value.title,
      feedbackContent: this.formEdit1.value.content
    };
    console.log(objReply);

    console.log(this.formEdit1.value);
    const uri = `user/postFeedback`;
    this._dataService.post(uri, objReply).subscribe(
      (data: any) => {
        this.getAllNotification();
        Swal.fire({
          icon: "success",
          title: "Send Feedback successful!",
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
