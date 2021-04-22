<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\Constants;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function login(Request $request) {
        $this->validate($request, [
            'client_id' => 'required',
            'client_secret' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'fcm_token' => 'required|string'
        ]);

        $params = [
            'grant_type' => 'password',
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*'
        ];
        $nRequest = Request::create('/oauth/token', 'POST', $params);
        $response = app()->handle($nRequest);

        if($response->getStatusCode() == Constants::RESPONSE_SUCCESS) {
            $loginResponse = $response->getContent();
            return Constants::successResponseWithNewValue('data', $loginResponse);
        }

        return Constants::errorResponse();
    }

    public function register(Request $request) {
        $this->validate($request, [
            'nama' => 'required|string|between:3,255',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'img' => 'image'
        ]);

        $user = User::create([
            'nama',
            'email',
            'password'
        ]);

        if(!empty($request->img)) {
            $user->saveImg($request->img);
        }

        return Constants::successResponse();
    }
}
