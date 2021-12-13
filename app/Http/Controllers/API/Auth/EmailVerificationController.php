<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Responders\ApiResponse;
use App\Models\EmailVerification;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailTokenRequest;
use App\Http\Requests\EmailTokenVerificationRequest;

class EmailVerificationController extends Controller
{
    public function sendEmailToken(EmailTokenRequest $request)
    {
        /**
         * @var EmailVerification
         */
        $emailVerification = EmailVerification::where('email', $request->email)->firstOr(function () use ($request) {
            return (new EmailVerification())->newVerificationEmail($request->email);
        });
        // Don't send a failure if the email exists.. 
        // So hackers don't know if an email exist or not

        // Email Already verified but not registered? regenerate token
        if (($emailVerification->isVerified() && !(User::where('email', $request->email)->exists())) || $emailVerification->isExpired()) {
            $emailVerification->resendVerificationToken();
        }

        return ApiResponse::success('success', ["token" => $emailVerification->token]);
    }

    public function verifyEmail(EmailTokenVerificationRequest $request)
    {
        /**
         * @var EmailVerification
         */
        $emailVerification = EmailVerification::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (
            empty($emailVerification) ||
            ($emailVerification->isVerified() && User::where('email', $request->email)->exists()) ||
            $emailVerification->isExpired()
        ) {
            return ApiResponse::validation(__("auth.token"), [
                'token' => [
                    __("auth.token")
                ]
            ]);
        }

        // Verify the email and move on
        if ($emailVerification->token == $request->token) {
            $emailVerification->email_verified_at = now();
            $emailVerification->save();

            return ApiResponse::success('success', [
                'email' => $emailVerification->email
            ]);
        }

        return ApiResponse::validation(__("auth.token"), [
            'token' => [
                __("auth.token")
            ]
        ]);
    }
}
