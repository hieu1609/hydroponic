<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use App\User;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use JWTFactory;
use App\PasswordReset;
use App\Notifications\ResetPasswordRequest;
use App\Notifications\ResetPasswordSuccess;
use Carbon\Carbon;
use Symfony\Component\Mime\Email;

class AuthController extends BaseApiController
{
    public function login(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/auth/login",
         *     description="Token will be return after login success",
         *     tags={"Auth"},
         *     summary="Login",
         *      @SWG\Parameter(
         *          name="body",
         *          description="Login",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="email",
         *                  type="string",
         *              ),
         *              @SWG\property(
         *                  property="password",
         *                  type="string",
         *              )
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=400, description="Bad request"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=404, description="Not Found"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         *
         */

        try {
            $validator = User::validate($request->all(), 'Rule_Signin');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $user = User::where(['email' => $request->email])->first();
            if (!$user) {
                return $this->responseErrorCustom("user_email_or_password_incorrect", 401);
            }

            if ($request->admin) {
                if (!$user->admin) {
                    return $this->responseErrorCustom("user_priority", 403);
                }
                $credentials = $request->only('email', 'password', 'admin');
            } else {
                $credentials = $request->only('email', 'password');
            }

            //create token
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return $this->responseErrorCustom("user_email_or_password_incorrect", 401);
            }

            $result = [
                'user' => $user,
                'token' => $token,
            ];
            return $this->responseSuccess($result);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function logout(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/auth/logout",
         *     description="Logout",
         *     tags={"Auth"},
         *     summary="Logout",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            JWTAuth::invalidate($request->header('token'));
            return $this->responseSuccess("Logout successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function register(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/auth/register",
         *     description="User registration",
         *     tags={"Auth"},
         *     summary="User registration",
         *
         *      @SWG\Parameter(
         *          name="body",
         *          description="User information",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="username",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="email",
         *                  type="string",
         *              ),
         *              @SWG\property(
         *                  property="password",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="confirmPassword",
         *                  type="string",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=404, description="Not Found"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = User::validate($request->all(), 'Rule_Create_User');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $user = new User;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->save();
            return $this->responseSuccess("Register_Successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function changePassword(Request $request)
    {
        /**
         * @SWG\Put(
         *     path="/auth/change-password",
         *     description="Change password",
         *     tags={"Auth"},
         *     summary="Change password",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Parameter(
         *          name="body",
         *          description="Enter recent password and new password with confirm",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="currentPassword",
         *                  type="string",
         *              ),
         *              @SWG\property(
         *                  property="newPassword",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="confirmNewPassword",
         *                  type="string",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = User::validate($request->all(), 'Rule_ChangPassword');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $user = $request->user;
            $email = $user->email;
            $currentPassword = $request->currentPassword;

            if (Auth::attempt(array('email' => $email, 'password' => $currentPassword))) {
                $newPassword = bcrypt($request->newPassword);
                $user->update(['password' => $newPassword]);
                return $this->responseSuccess("Change password successfully");
            } else {
                return $this->responseErrorCustom("user_password_invalid", 401); //current password is wrong
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function requestResetPassword(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/auth/request/reset-password",
         *     description="Send email attach token",
         *     tags={"Auth"},
         *     summary="Send request reset password",
         *      @SWG\Parameter(
         *          name="body",
         *          description="Email to reset",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="email",
         *                  type="string",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=404, description="Not Found"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = PasswordReset::validate($request->all(), 'Rule_RequestResetPassword');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $email = $request->email;
            $user = User::where('email', $email)->first();
            if (!$user) {
                return $this->responseErrorCustom("users_not_found", 404);
            }

            $passwordReset = PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => str_random(60),
                    'expires_at' => Carbon::now()->addMinutes(120),
                ]
            );
            if (!$passwordReset) {
                return $this->responseErrorCustom("tokens_reset_password_not_create");
            }

            $user->notify(
                new ResetPasswordRequest($passwordReset->token, $user->admin)
            );
            return $this->responseSuccess("Sent email to reset password.");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function acceptResetPassword(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/auth/accept/reset-password",
         *     description="Finish reset password",
         *     tags={"Auth"},
         *     summary="Accept request reset password",
         *      @SWG\Parameter(
         *          name="body",
         *          description="Reset Password",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="token",
         *                  type="string",
         *              ),
         *              @SWG\property(
         *                  property="newPassword",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="confirmNewPassword",
         *                  type="string",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=404, description="Not Found"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = PasswordReset::validate($request->all(), 'Rule_AcceptResetPassword');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $token = $request->token; //token checked in Rule
            $passwordReset = PasswordReset::where('token', $token)->first();
            if (!$passwordReset) {
                return $this->responseErrorCustom("tokens_reset_password_invalid", 401);
            }

            if (Carbon::parse($passwordReset->expires_at)->isPast()) {
                return $this->responseErrorCustom("tokens_expired", 401);
            }

            $user = User::where('email', $passwordReset->email)->first();
            if (!$user) {
                return $this->responseErrorCustom("tokens_reset_password_invalid_credentials", 401);
            }

            $user->password = bcrypt($request->newPassword);
            $user->save();
            $passwordReset->delete();
            $user->notify(new ResetPasswordSuccess($passwordReset));
            return $this->responseSuccess("Reset password successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }
}
