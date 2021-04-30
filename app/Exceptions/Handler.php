<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    // public function render($request, Exception $exception)
    // {
    //     $response = null;

        // if (!$exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        //     $response = [
        //         'error' => 'Sorry, something went wrong.',
        //         'status' => '500'
        //     ];

        // }
        // else
        // {
        //     $response = [
        //         'error' => 'This URL does not exist',
        //         'status' => '404'
        //     ];
        // }
        // if ($request->expectsJson()) {
        //     return response()->json($response, '200');
        // } else {
        //     return response($response, '200');
        // }
    // }
}
