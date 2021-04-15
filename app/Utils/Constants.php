<?php


namespace App\Utils;

use Illuminate\Support\Facades\Response;

/**
 * String Util, Jaunary 22, 2020
* These codes originally are coded and initiated by Amir Mu'tashim Billah
* you can find me out on my Linkedin Account
* https://www.linkedin.com/in/amirmb/
 */

class Constants
{
    const RESPONSE_SUCCESS = 200;
    const RESPONSE_NEED_REFRESH = 100;
    const RESPONSE_ERROR = 400;
    const RESPONSE_ACCESS_DENIED = 403;
    const RESPONSE_DATA_ALREADY_EXIST = 103;
    const RESPONSE_INCOMPLETE_REQUEST = 104;
    const RESPONSE_DATA_EMPTY = 106;

    const USER_GROUP_ADMIN = 1;
    const USER_GROUP_DOKTER = 2;
    const USER_GROUP_PASIEN = 3;

    public static function successResponse($message = 'response success') {
        return Response::json(['message' => $message, 'status' => 'success', 'code' => self::RESPONSE_SUCCESS], self::RESPONSE_SUCCESS);
    }

    public static function successResponseWithNewValue($newKey, $newVal, $message = 'response success') {
        $responseMsg = ['message' => $message, 'status' => 'success', 'code' => self::RESPONSE_SUCCESS];
        $responseMsg[$newKey] = $newVal;
        return Response::json($responseMsg, self::RESPONSE_SUCCESS);
    }

    public static function errorResponse($message = 'an error occurred') {
        return Response::json(['message' => $message, 'status' => 'error', 'code' => self::RESPONSE_ERROR], self::RESPONSE_ERROR);
    }

    public static function errorResponseWithNewValue($newKey, $newVal, $message = 'an error occurred') {
        $responseMsg = ['message' => $message, 'status' => 'error', 'code' => self::RESPONSE_ERROR];
        $responseMsg[$newKey] = $newVal;
        return Response::json($responseMsg, self::RESPONSE_ERROR);
    }

    public static function accessDeniedResponse($message = 'access denied') {
        return Response::json(['message' => $message, 'status' => 'error', 'code' => self::RESPONSE_ACCESS_DENIED], self::RESPONSE_ACCESS_DENIED);
    }

    public static function emptyResponseWithNewValue($newKey, $newVal, $message = 'data empty') {
        $responseMsg = ['message' => $message, 'status' => 'error', 'code' => self::RESPONSE_DATA_EMPTY];
        $responseMsg[$newKey] = $newVal;
        return Response::json($responseMsg, self::RESPONSE_DATA_EMPTY);
    }

    public static function emptyResponse($message = 'data empty') {
        return Response::json(['message' => $message, 'status' => 'error', 'code' => self::RESPONSE_DATA_EMPTY], self::RESPONSE_DATA_EMPTY);
    }

    public static function incompleteResponse($message = 'incomplete field') {
        return Response::json(['message' => $message, 'status' => 'error', 'code' => self::RESPONSE_INCOMPLETE_REQUEST], self::RESPONSE_INCOMPLETE_REQUEST);
    }

    public static function alreadyExistsResponse($message = 'data already exists') {
        return Response::json(['message' => $message, 'status' => 'error', 'code' => self::RESPONSE_DATA_ALREADY_EXIST], self::RESPONSE_DATA_ALREADY_EXIST);
    }

    public static function needRefreshResponse($message = 'data need to refresh') {
        return Response::json(['message' => $message, 'status' => 'error', 'code' => self::RESPONSE_NEED_REFRESH],self::RESPONSE_NEED_REFRESH);
    }
}
