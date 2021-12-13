<?php

namespace App\Responders;

class HttpResponseModel
{
    public bool $status;
    public int $statusCode;
    public string $message;
    public string $error;
    public array $errors;
    public ?\Exception $exception;
    public $data;

    public function __construct(
        bool $status,
        int $statusCode,
        $data,
        string $message,
        string $error = '',
        array $errors = [],
        $exception = null
    ) {
        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->error = $error;
        $this->errors = $errors;
        $this->data = $data;
        $this->exception = $exception;
    }
}
