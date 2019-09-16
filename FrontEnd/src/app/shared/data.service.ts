import { Injectable } from "@angular/core";
import { environment } from "../../environments/environment";
import { HttpHeaders, HttpClient } from "@angular/common/http";
import { Observable, throwError } from "rxjs";
import { tap, catchError } from "rxjs/operators";
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
  constructor(private http: HttpClient) {
    urlApi = environment.urlApi;
  }
  HandleError(errCode) {
    console.log(errCode.error);
    switch (errCode.status) {
      case 500:
        console.log(errCode.error);
        break;
      case 404:
        console.log(errCode.error);
        break;
      case 401:
        console.log(errCode.error);
        break;
      default:
        break;
    }
    return throwError(errCode);
  }
  get(uri: string): Observable<any> {
    return this.http.get(urlApi + "/" + uri).pipe(
      tap((data: any) => {
        console.log(data);
      }),
      catchError(error => {
        return this.HandleError(error);
      })
    );
  }
  post(uri: string, data?: any): Observable<any> {
    return this.http.post(urlApi + "/" + uri, data, httpOptions).pipe(
      tap((data: any) => {
        console.log(data);
      }),
      catchError(error => {
        return this.HandleError(error);
      })
    );
  }
}
