<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionsTrait {
    public function apiExceptions($request, $exception){
		if($this->isNotFound($exception)) return $this->notFoundExceptionHandler();
		if($this->isPending($exception)) return $this->pendingExceptionHandler();
		if($this->isHttp($exception)) return $this->httpExceptionHandler();
        if($this->isModel($exception)) return $this->modelExceptionHandler();

		return parent::render($request, $exception);
    }
    private function notFoundExceptionHandler(){
        return response()->json([
            'error'   => true,
            'message' => 'Query Not Found',
            'status'  => Response::HTTP_NOT_FOUND,
        ], Response::HTTP_NOT_FOUND);
	}
    private function pendingExceptionHandler(){
        return response()->json([
            'error'   => true,
            'message' => 'Query Not Available',
            'status'  => Response::HTTP_NOT_FOUND,
        ], Response::HTTP_NOT_FOUND);
	}
    private function httpExceptionHandler(){
		return response()->json([
            'error'   => true,
            'message' => 'Request Not Found',
            'status'  => Response::HTTP_NOT_FOUND,
		], Response::HTTP_NOT_FOUND);
	}
	private function modelExceptionHandler(){
		return response()->json([
            'error'   => true,
            'message' => 'Model Not Found',
            'status'  => Response::HTTP_NOT_FOUND,
		], Response::HTTP_NOT_FOUND);
	}
	private function isNotFound($e){return $e instanceof QueryNotFoundException;}
	private function isPending($e){return $e instanceof QueryPendingException;}
	private function isHttp($e){return $e instanceof NotFoundHttpException;}
	private function isModel($e){return $e instanceof ModelNotFoundException;}

}
