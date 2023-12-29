<?php 

namespace App\Helpers;

class ResponseFormatter 
{
    public static function format($status, $message, $data = null)
    {
        return [
            "status"  => $status,
            "message" => $message,
            "data"    => $data
        ];
    }
}