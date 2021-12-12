<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Responders\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $fillable = $request->validated();
            $fillable['email_verified_at'] = now();
            $fillable['password'] = bcrypt($request->password);

            $user = User::create($fillable);
            $token = $user->createToken($request->device_name)->plainTextToken;
            $cookie = cookie('jwt', $token, 60 * 720); //30days

            DB::commit();
            return ApiResponse::success(
                'success',
                ['user' => $user, 'token' => $token]
            )->withCookie($cookie);
        } catch (\Exception $e) {
            DB::rollback();
            return ApiResponse::failure($e->getMessage(), $e, [], 400);
        }
    }
}
