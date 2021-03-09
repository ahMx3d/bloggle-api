<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class QueryNotFoundException extends Exception
{
    protected $message;
    protected $code;
    public function __construct($message = 'Query is not found'){
        $this->message = $message;
        $this->code = Response::HTTP_NO_CONTENT;
    }
}
