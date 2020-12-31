<?php

namespace App\Services;

class ExceptionHandler
{
    public static function notPermission()
    {
        $error = [
            'message' => 'You are not allowed'
        ];
        return response()->json($error, 403);
    }
}
