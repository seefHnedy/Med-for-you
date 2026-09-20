<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function render()
    {
        $code = 422;

        $response = [
            'success' => false,
            'message' => $this->getMessage(),
            'code' => $code,
        ];

        return response()->json($response, $code);

    }
}
