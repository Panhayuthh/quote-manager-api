<?php

namespace App\Classes;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class ApiResponseClass
{
    /**
     * Create a new class instance.
     */
    public static function sendResponse($result , $message){
        $response=[
            'data'    => $result
        ];
        if(!empty($message)){
            $response['message'] =$message;
        }
        return response()->json($response);
    }

    public static function rollback($e, $message = 'An error occurred')
    {
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw($e, $message = 'An error occurred')
    {
        throw new HttpResponseException(response()->json([
            'message' => $message,
            'error' => $e->getMessage()
        ]));
    }
}
