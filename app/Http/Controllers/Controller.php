<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    public $pagination = 15;

    protected function adminValidator($roles, $data)
    {
        $validator = validator($data, $roles);

        if ($validator->fails())
            return $this->validationFail($validator->errors()->toArray());
        else
            return true;
    }

    public function success($data, $status_code = 200)
    {

        return $this->response([
            'status' => 'success',
            'status_code' => $status_code,
            'data' => $data
        ], 200);
    }

    public function validationFail($errors){
        return $this->fail('validation_error', "Validation Errors", $errors, 400);

    }


    public function fail($code = 'internal_error', $msg = "Internal Server Error", $errors = [], $status_code = 500)
    {
        return $this->response([
            'status' => 'fail',
            'status_code' => $status_code,
            'error_code' => $code,
            'message' => $msg,
            'error' => $errors
        ], $status_code);
    }


    public function response($data, $status = 200)
    {
        return response()->json($data, $status);
    }



    public function filterStrings($query, $columns, $inputs)
    {

        foreach ($inputs as $key => $value)
            (in_array($key, $columns)) ? $query = $query->where($key, 'like', '%' . $value . '%') : '';

        return $query;
    }
}
