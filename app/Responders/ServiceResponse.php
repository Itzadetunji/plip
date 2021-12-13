<?php

namespace App\Responders;

use App\Responders\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Enums\ServiceResponseType;


class ServiceResponse
{
    public bool  $status;
    public ServiceResponseType $type;
    public string $message;
    public array $data;
    public array $errors;

    public function __construct(
        bool $status,
        ServiceResponseType $type,
        string $message,
        $data = [],
        $errors = []
    ) {
        $this->status = $status;
        $this->type = $type;
        $this->message = $message;
        $this->data = $data;
        $this->errors = $errors;
    }

    /**
     * Returns a HTTP representation of the service response
     */
    public function toHttpResponse(): JsonResponse
    {
        if ($this->status) {
            return ApiResponse::success($this->message, $this->data);
        }

        if (ServiceResponseType::VALIDATION()->is($this->type)) {
            return ApiResponse::validation($this->message, $this->errors);
        }

        if (ServiceResponseType::ERROR()->is($this->type)) {
            return ApiResponse::failure($this->message, null, $this->errors, 400);
        }

        return ApiResponse::failure('Unable to process request');
    }
}
