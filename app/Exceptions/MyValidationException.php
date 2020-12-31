<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class MyValidationException extends Exception
{
    protected $validator;
    protected $errorBag;
    protected $code;

    public function __construct($validator,$errorBag)
    {
        $this->validator = $validator;
        $this->errorBag = $errorBag;
    }

    public function errorBag($errorBag)
    {
        $this->errorBag = $errorBag;
        return $this;
    }

    public function render(){
        return response()->json([
            "error" => "Form validation error",
            "status" => $this->validator->errors()->first()
        ], $this->code);
    }
}
