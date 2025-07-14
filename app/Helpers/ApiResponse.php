<?php

if (!function_exists('successResponse')) {
    function successResponse($data = null, $message = 'Successfully Retrived data', $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}

if (!function_exists('errorResponse')) {
    function errorResponse($message = 'Error', $status = 400, $errors = [])
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
if (!function_exists('notFoundResponse')) {
    function notFoundResponse($message = 'Not Found', $status = 404)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $status);
    }
}
