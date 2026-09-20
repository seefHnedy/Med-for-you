<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function sendResponse($message, $data = [], $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }


    protected function sendPagination($message,  $data = [],$code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'per_page' => $data['per_page'],
            'total' => $data['total'],
            'current_page' => $data['current_page'],
            'last_page' => $data['last_page'],
            'data' => $data['data'],
        ];
        return response()->json($response, $code);
    }
}
