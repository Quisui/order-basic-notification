<?php

namespace Quisui\OrderBasicNotification\Exceptions;

use Exception;

class CustomValidationException extends Exception
{
    public $message;
    public $statusCode;

    public function __construct($message, $statusCode)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }
    public function render($request)
    {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $this->message,
        ], $this->statusCode);
    }
}
