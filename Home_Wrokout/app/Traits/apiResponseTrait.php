<?php


namespace App\Traits;


trait apiResponseTrait
{

    public function apiResponse($data = null, $message = null, $status = null, $token = null)
    {

        if ($token != null) {

            $array = [
                'token' => $token,
                'status ' => $status,
                'message ' => $message,
                'data ' => $data,
            ];
        } else {

            $array = [
                'status ' => $status,
                'message ' => $message,
                'data ' => $data,
            ];
        }


        return response($array);
    }
}
