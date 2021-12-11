<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use App\Responders\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return ApiResponse::notFound();
        } elseif ($e instanceof NotFoundHttpException) {
            return ApiResponse::notFound();
        } elseif ($e->getCode() == 426) {
            return ApiResponse::upgrade($e->getMessage());
        } elseif ($e instanceof AuthorizationException) {
            return ApiResponse::unauthorized($e->getMessage());
        } elseif ($e instanceof ValidationException) {
            return ApiResponse::validation($e->getMessage(), $e->errors());
        } elseif ($e instanceof ThrottleRequestsException) {
            return ApiResponse::abort(429, $e->getMessage());
        }

        return parent::render($request, $e);
    }
}
