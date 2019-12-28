import {
  HttpInterceptor,
  HttpRequest,
  HttpHandler
} from "@angular/common/http";
import { Injectable } from "@angular/core";

@Injectable()
export class AuthInterceptor implements HttpInterceptor {
  constructor() {}

  intercept(req: HttpRequest<any>, next: HttpHandler) {
    // Get the auth token from the service.
    let authToken = "";
    if (localStorage.getItem("user")) {
      const user = JSON.parse(localStorage.getItem("user"));

      authToken = user.data.token;
    }
    // if (sessionStorage.getItem("admin")) {
    //   const user = JSON.parse(sessionStorage.getItem("admin"));

    //   authToken = user.data.token;
    // }
    // Clone the request and replace the original headers with
    // cloned headers, updated with the authorization.
    const authReq = req.clone({
      headers: req.headers.set("Authorization", `Bearer ${authToken}`)
    });

    // send cloned request with header to the next handler.
    return next.handle(authReq);
  }
}
