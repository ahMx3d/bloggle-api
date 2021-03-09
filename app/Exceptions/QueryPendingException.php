<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class QueryPendingException extends Exception{
    protected $message;
    protected $code;
    public function __construct($message = 'Query is not available'){
        $this->message = $message;
        $this->code = Response::HTTP_NO_CONTENT;
    }
}
