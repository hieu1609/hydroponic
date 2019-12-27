import { Injectable } from "@angular/core";
import { environment } from "../../environments/environment";
import { HttpHeaders, HttpClient } from "@angular/common/http";
import { Observable, throwError } from "rxjs";
import { tap, catchError } from "rxjs/operators";
import { Router } from "@angular/router";
import Swal from "sweetalert2";
let urlApi;
const httpOptions = {
  headers: new HttpHeaders({
    "Content-Type": "application/json"
  })
};
@Injectable({
  providedIn: "root"
})
export class DataService {
  constructor(private http: HttpClient, private router: Router) {
    urlApi = environment.urlApi;
  }
  HandleError(errCode) {
    console.log(errCode.error);
    switch (errCode.status) {
      case 500:
        console.log(errCode.error);
        Swal.fire({
          icon: "error",
          title: "Opps...",
          text: errCode.error.errors[0].errorMessage,
          showConfirmButton: false,
          timer: 2500
        });

        break;
      case 404:
        console.log(errCode.error);
        break;
      case 401:
        if (errCode.error.errors[0].errCode === 3002) {
          this.router.navigate(["admin"]);
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Login session expired",
            showConfirmButton: false,
            timer: 2500
          });
        } else if (errCode.error.errors[0].errCode === 2076) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: errCode.error.errors[0].errorMessage,
            showConfirmButton: false,
            timer: 2500
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: errCode.error.errors[0].errorMessage,
            showConfirmButton: false,
            timer: 2500
          });
          this.router.navigate(["admin"]);
        }
        break;
      case 403:
        Swal.fire({
          icon: "error",
          title: errCode.error.errors[0].errorMessage,
          showConfirmButton: false,
          timer: 2500
        });
        break;
      case 422:
        Swal.fire({
          icon: "error",
          title: "Something went wrong ",
          text: errCode.error.errors[0].errorMessage,
          showConfirmButton: false,
          timer: 2500
        });
        break;
      default:
        Swal.fire({
          icon: "error",
          title: "Opps...",
          text: errCode.error.errors[0].errorMessage,
          showConfirmButton: false,
          timer: 2500
        });
        break;
    }
    return throwError(errCode);
  }
  get(uri: string): Observable<any> {
    return this.http.get(urlApi + "/" + uri).pipe(
      tap((data: any) => {}),
      catchError(error => {
        return this.HandleError(error);
      })
    );
  }
  post(uri: string, data?: any): Observable<any> {
    return this.http.post(urlApi + "/" + uri, data, httpOptions).pipe(
      tap((data: any) => {}),
      catchError(error => {
        return this.HandleError(error);
      })
    );
  }
  put(uri: string, data?: any): Observable<any> {
    return this.http.put(urlApi + "/" + uri, data, httpOptions).pipe(
      tap((data: any) => {}),
      catchError(error => {
        return this.HandleError(error);
      })
    );
  }
  delete(uri: string): Observable<any> {
    return this.http.delete(urlApi + "/" + uri, httpOptions).pipe(
      tap((data: any) => {}),
      catchError(error => {
        return this.HandleError(error);
      })
    );
  }
}
