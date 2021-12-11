<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Responders\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        $request->authenticate();
        $token = $request->user()->createToken($request->device_name)->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 720); //30days

        return ApiResponse::success(
            'success',
            ['user' => $request->user(), 'token' => $token]
        )->withCookie($cookie);
    }
}
