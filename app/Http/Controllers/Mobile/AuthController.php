<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\Constants;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $nRequest->headers->set('Accept', 'application/json');
        $response = app()->handle($nRequest);

        if($response->getStatusCode() == Constants::RESPONSE_SUCCESS) {
            $loginResponse = $response->getContent();
            $user = User::where('email', $request->email)->first();
            $user->fcm_token = $request->fcm_token;
            $user->save();

            return Constants::successResponseWithNewValue('data', json_decode($loginResponse), 'user successfully logged in');
        }

        return Constants::errorResponse();
    }

    public function logout(Authenticatable $user) {
        $user->revokeFCM();
        $user->revokeApiToken();
        return Constants::successResponse('user has logged out');
    }

    public function register(Request $request) {
        $this->validate($request, [
            'nama' => 'required|string|between:3,255',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'img' => 'image'
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email'=> $request->email,
            'password' => Hash::make($request->password),
            'user_group_id' => Constants::USER_GROUP_BIDAN
        ]);

        if(!empty($request->img)) {
            $user->saveImg($request->img);
        }

        return Constants::successResponseWithNewValue('data', $user, 'user successfully registered');
    }

    public function checkEmail(Request $request) {
        try {
            $request->validate([
                'email'
            ]);
            $exist = User::where('email', $request->email)->first();

            if(empty($exist))
                return Constants::successResponseWithNewValue('available', true);

            return Constants::successResponseWithNewValue('available', false);

        } catch (\Exception $e) {
            return Constants::successResponseWithNewValue('available', false);
        }
    }
}
