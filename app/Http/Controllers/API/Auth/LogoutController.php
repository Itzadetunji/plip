<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Responders\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $cookie = Cookie::forget('jwt');

        return ApiResponse::success('success')->withCookie($cookie);
    }
}
