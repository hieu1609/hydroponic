import { Component, OnInit, ViewChild } from "@angular/core";
import { DataService } from "src/app/shared/data.service";
import { Router } from "@angular/router";
import { NgForm } from "@angular/forms";
import Swal from "sweetalert2";

@Component({
  selector: "app-feedback",
  templateUrl: "./feedback.component.html",
  styleUrls: ["./feedback.component.scss"]
})
export class FeedbackComponent implements OnInit {
  constructor(private _dataService: DataService, private router: Router) {}
  @ViewChild("formDetail", { static: false }) formDetail: NgForm;
  @ViewChild("formEdit", { static: false }) formEdit: NgForm;
  feedbacksList: any = [];
  feedbacksListOverall: any = [];
  feedbacksListUnread: any = [];
  feedbacksListReceived: any = [];
  idFeedbackEdit;
  totalPage: any = [];
  currentPage;
  ngOnInit() {
    this.getAllFeedbacks(1);
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
  getAllFeedbacks(page) {
    this.currentPage = page;
    const uri = `admin/getFeedbackAdmin`;
    let message = {
      page
    };
    this._dataService.post(uri, message).subscribe(
      (data: any) => {
        this.feedbacksList = data.data.data;
        this.feedbacksListOverall = data.data.data;
        if (this.feedbacksList.length === 0 && page !== 1) {
          this.getAllFeedbacks(page - 1);
        }
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
  Seen(objFeedBack) {
    const uri = `admin/notification/${this.idFeedbackEdit}`;
    this._dataService.put(uri, objFeedBack).subscribe(
      (data: any) => {
        this.getAllFeedbacks(this.currentPage);
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
    this.Seen(objFeedBack);
  }
  ReplyFeedbacks(item) {
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
  SendNotification(item) {
    console.log(item);

    this.idFeedbackEdit = item.id;
    this.formEdit.setValue({
      userid: item.user_id_send,
      title: item.title,
      content: null
    });

    console.log(this.formEdit.value);
  }
  _handleOnSubmitEditForm() {
    let objReply = {
      userId: this.formEdit.value.userid,
      notificationTitle: this.formEdit.value.title,
      notificationContent: this.formEdit.value.content
    };
    console.log(objReply);

    console.log(this.formEdit.value);
    const uri = `admin/sendNotification`;
    this._dataService.post(uri, objReply).subscribe(
      (data: any) => {
        this.getAllFeedbacks(this.currentPage);
        Swal.fire({
          icon: "success",
          title: "Reply feedback successful!",
          showConfirmButton: false,
          timer: 1500
        });
      },
      (err: any) => {
        console.log(err);
      }
    );
  }
  DeleteFeedbacks(item) {
    this.idFeedbackEdit = item.id;
    const uri = `admin/notification/${this.idFeedbackEdit}`;
    this._dataService.delete(uri).subscribe(
      (data: any) => {
        this.getAllFeedbacks(this.currentPage);
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
}
