<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use File;

class BaseApiController extends Controller
{
    protected  $statusCode;
    protected  $result = [];
    protected  $apiError;

    public function __construct()
    {
        $this->result = [
            'error' => false,
            'data' => null,
            'errors' => []
        ];
        $this->statusCode = 200;
        $this->apiError = Lang::get('errorCodeApi');
    }
    private function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
    private function setErrorResult($errors)
    {
        $this->result['error'] = true;
        $this->result['errors'] = $errors;
        return $this;
    }
    private function setSuccessResult($data)
    {
        $this->result['data'] = $data;
        return $this;
    }
    public function responseSuccess($data = array())
    {
        $this->setSuccessResult($data);
        return response()->json($this->result, $this->statusCode);
    }
    public function responseErrorValidator($errors = array(), $httpcode = 500)
    {
        $this->setStatusCode($httpcode);
        $this->setErrorResult($errors);
        return response()->json($this->result, $this->statusCode);
    }

    public function responseErrorException($errorMessage, $apicode = 99999, $httpcode = 500)
    {
        $this->setStatusCode($httpcode);
        $errors[0] = [
            'errorCode' => $apicode,
            'errorMessage' => $errorMessage
        ];
        $this->setErrorResult($errors);
        return response()->json($this->result, $this->statusCode);
    }
    public function responseErrorCustom($errorCode, $httpcode = 500)
    {
        $this->setStatusCode($httpcode);
        $errors[0] = [
            'errorCode' => $this->apiError['ApiErrorCodes'][$errorCode],
            'errorMessage' => $this->apiError['ApiErrorMessages'][$errorCode]
        ];

        $this->setErrorResult($errors);
        return response()->json($this->result, $this->statusCode);
    }

    public function saveImage(Request $request, $type, $oldPath = null)
    {
        switch ($type) {
            case "user": {
                    $folderUpload = 'upload/user/image/avatar/';
                    $image = 'avatar';
                    $default = $folderUpload.'default.png';
                    break;
                }
            case "ask": {
                    $folderUpload = 'upload/ask/image/';
                    $image = 'askImage';
                    $default = null;
                    break;
                }
            case "video": {
                    $folderUpload = 'upload/video/image/';
                    $image = 'videoImage';
                    $default = $folderUpload.'default.png';
                    break;
                }
            case "learn": {
                    $folderUpload = 'upload/learn/image/';
                    $image = 'learnImage';
                    $default = $folderUpload.'default.png';
                    break;
                }
            default:
                $folderUpload = 'upload/';
                $image = 'image';
        }
        if ($request->hasFile($image)) {
            $file = $request->file($image);
            $customName = time() . '_' . str_random(4) . '.' . $file->getClientOriginalExtension();
            while (file_exists($folderUpload . $customName)) {
                $customName = time() . '_' . str_random(4) . '.' . $file->getClientOriginalExtension();
            }
            
            $file->move($folderUpload, $customName);
            if (!($oldPath == 'upload/user/image/avatar/default.png')) {
                $oldPath = public_path($oldPath);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            return $folderUpload . $customName;
        }
        return $default;
    }
}
