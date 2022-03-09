<?php

namespace App\Helpers;

class Response
{
    /**
     * @param $message
     *
     * @return json
     */
    public static function successResponse(string $message)
    {
        return response()->json([
            'status' => [
                'code' => 200,
                'message' => $message
            ]
        ]);
    }

    /**
     * @param $message
     * @param mixed $data
     *
     * @return json
     */
    public static function successWithDataResponse(string $message, $data)
    {
        return response()->json([
            'status' => [
                'code' => 200,
                'message' => $message
            ],
            'data' => $data
        ]);
    }

    /**
     * @param string $message
     * @param array $errors
     *
     * @return json
     */
    public static function validationResponse(string $message, array $errors)
    {
        return response()->json([
            'status' => [
                'code' => 422,
                'message' => $message
            ],
            'errors' => $errors
        ], 422);
    }

    /**
     * @param string $message
     *
     * @return json
     */
    public static function badRequest(string $message)
    {
        return response()->json([
            'status' => [
                'code' => 400,
                'message' => $message
            ]
        ], 400);
    }

    /**
     * @param string $message
     *
     * @return json
     */
    public static function internalServerResponse(string $message = 'Terjadi kesalahan pada server')
    {
        return response()->json([
            'status' => [
                'code' => 500,
                'message' => $message
            ]
        ], 500);
    }

    /**
     * @param int $code
     * @param string $message
     *
     * @return json
     */
    public static function withCode(int $code, string $message)
    {
        return response()->json([
            'status' => [
                'code' => $code,
                'message' => $message
            ]
        ], $code);
    }
}
