<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //


    public function success($data)
    {

        return $this->response([
            'status' => 'success',
            'status_code' => 200,
            'data' => $data
        ], 200);
    }


    public function fail($code = 'internal_error', $msg = "Internal Server Error", $status = 500)
    {
        return $this->response([
            'status' => 'fail',
            'status_code' => $status,
            'error_code' => $code,
            'message' => $msg,
        ], $status);
    }


    public function response($data, $status = 200)
    {
        return response()->json($data, $status);
    }
}
