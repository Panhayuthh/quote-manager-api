<?php

namespace App\Classes;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class ApiResponseClass
{

    // custom response class
    public static function sendResponse($result , $message, $status = 200){
        $response=[
            'data' => $result
        ];
        if(!empty($message)){
            $response['message'] =$message;
        }
        return response()->json($response, $status);
    }

    // public static function rollback($e, $message = 'An error occurred', $status = 500)
    // {   
    //     // if error occurs, rollback the transaction
    //     DB::rollBack();
    //     self::throw($e, $message, $status);
    // }

    public static function sendError($e, $message = 'An error occurred', $status)
    {
        throw new HttpResponseException(response()->json([
            'message' => $message,
            'error' => $e->getMessage()
        ], $status));
    }
}
