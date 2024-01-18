<?php

function ApiResponse($message, $statusCode, $data = [])
{
    $response = [
        'status' => $statusCode,
        'message' => $message,
        'data' => $data ?? null,
    ];

    return response()->json($response, $statusCode);
}
