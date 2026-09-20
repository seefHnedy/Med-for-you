<?php

namespace App\Exceptions;

use Exception;

class UnauthenticatedException extends Exception
{
     public function render()
{
    $code = 401;

    $response = [
        'success' => false,
        'message' => $this->getMessage(),
        'code' => $code,
    ];

    return response()->json($response, $code);

}
}
