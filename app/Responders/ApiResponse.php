<?php

namespace App\Responders;

use App\Exceptions\Handler;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method static mixed success()
 * @method static mixed noContent()
 * @method static mixed failure()
 * @method static array apiPaginationFactory()
 * @method static mixed unauthorized()
 * @method static mixed badRequest()
 * @method static mixed abort()
 * @method static mixed conflict()
 * @method static mixed forbidden()
 * @method static mixed upgrade()
 * @method static mixed validation()
 */
class ApiResponse
{

    const RESULTS_PER_PAGE = 15;
    const RESPONDER = [
        'status' => false,
        'message' => '',
        'data' => [],
        'meta' => [],
    ];

    /**
     * Returns a not content [201] response
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function noContent(int $statusCode = 201): JsonResponse
    {
        return Response::json([], $statusCode);
    }

    /**
     * Returns a success [200] response
     * @param string $msg
     * @param array $data
     * @param array $meta
     * @return JsonResponse
     */
    public static function success(string $msg, $data = [], array $meta = []): JsonResponse
    {
        $nativeMeta = [];
        $pagination_aware_data = self::apiPaginationFactory($data);
        $payload = $pagination_aware_data['payload'];
        $pagination = $pagination_aware_data['pagination'];

        $responder = self::RESPONDER;
        $responder['status'] = true;
        $responder['message'] = $msg;
        $responder['data'] = $payload;
        $responder['meta'] = $meta + $nativeMeta;
        $responder['pagination'] = $pagination ?? [];

        return Response::json($responder);
    }

    /**
     * Returns a failure [4xx,5xx] response
     * @param null $exception
     * @param string $msg
     * @param array $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function failure(string $msg, $exception = null, $errors = [], $statusCode = 500): JsonResponse
    {
        $responder = self::RESPONDER;
        $responder['status'] = false;
        $responder['message'] = $msg;
        $responder['errors'] = $errors;

        if (!is_null($exception)) {
            //accept direct exceptions classes
            if (is_object($exception)) {
                $exception = get_class($exception);
            }
            //do more logic
        }

        return Response::json($responder, $statusCode);
    }

    /**
     * Separates eloquent paginated results
     * into a data and pagination data
     * @param $data
     * @return array
     */
    public static function apiPaginationFactory($data): array
    {
        $payload = [];
        $pagination = [];

        // to cater for pagination data
        if (is_object($data)) {
            switch (get_class($data)) {
                case LengthAwarePaginator::class:
                    $pagination = $data->toArray();
                    $payload = $pagination['data'];
                    unset($pagination['data']);
                    break;

                default:
                    $payload = $data;
                    break;
            }
        } else {
            $payload = $data;
        }

        return [
            'payload' => $payload,
            'pagination' => $pagination
        ];
    }

    /**
     * Aborts a request
     * @param int|null $statusCode
     * @param string|null $message
     * @return JsonResponse
     */
    public static function abort(?int $statusCode = 400, ?string $message = 'Malformed request'): JsonResponse
    {
        return self::failure($message, null, [], $statusCode)->send();
    }

    /**
     * Aborts a request
     * @param null $message
     * @return JsonResponse
     */
    public static function badRequest(string $message = null): JsonResponse
    {
        $message = $message ?? 'Bad Request';

        return self::failure(
            $message,
            new BadRequestException($message),
            [],
            400
        );
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public static function notFound($message = 'Resource not found'): JsonResponse
    {
        return self::failure(
            $message,
            new NotFoundHttpException($message),
            [],
            404
        );
    }

    public static function applicationKey(): string
    {
        return request()->bearerToken();
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public static function unauthorized($message = 'Unauthorized access', $errors = []): JsonResponse
    {
        return self::failure(
            $message,
            new UnauthorizedException($message),
            $errors,
            401
        );
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public static function conflict($message = 'Item already exists'): JsonResponse
    {
        return self::failure(
            $message,
            new ConflictHttpException($message),
            [],
            409
        );
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public static function forbidden($message = 'You do not have access to this content'): JsonResponse
    {
        return self::failure(
            $message,
            new UnauthorizedException($message),
            [],
            403
        );
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public static function upgrade($message = 'You need to upgrade your account to access this feature'): JsonResponse
    {
        return self::failure(
            $message,
            new Exception($message),
            [],
            426
        );
    }

    /**
     * @param $message
     * @param $errors
     * @return JsonResponse
     */
    public static function validation($message, $errors = []): JsonResponse
    {
        return self::failure(
            $message,
            ValidationException::withMessages($errors),
            $errors,
            422
        );
    }
}
