<?php

namespace App\Library;

class Response
{

    public static function Success($data = null, $code = 200)
    {
        return response()->json(
            [
                'status' => 'success',
                'data' => $data,

            ], $code);
    }

    public static function Error($data = null, $code = 400)
    {
        return response()->json(
            [
                'status' => 'error',
                'message' => $data,

            ], $code);
    }
}
