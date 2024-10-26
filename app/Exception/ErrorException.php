<?php

namespace App\Exception;

use Exception;

class ErrorException extends Exception
{
    public function __construct(string $message, public array $data = [], int $code = 404)
    {
        parent::__construct($message, $code);
    }

    public function sendErrorResponse()
    {
        $response = [
            'success' => false,
            'message' => $this->message,
        ];

        if (!empty($this->data)) {
            $response['data'] = $this->data;
        }

        return response()->json($response, $this->code);
    }
}
