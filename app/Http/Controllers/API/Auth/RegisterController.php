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

            User::create($fillable);

            DB::commit();
            return ApiResponse::success('success');
        } catch (\Exception $e) {
            DB::rollback();
            return ApiResponse::failure($e->getMessage(), $e, [], 400);
        }
    }
}
