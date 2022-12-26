<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\LineMessageTrait;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    use LineMessageTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param $request
     * @param  \Throwable  $e
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     * @Author: Roy
     * @DateTime: 2022/12/26 上午 10:27
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            $this->sendMessage(sprintf("url : %s ,message : %s", $request->getUri, $e->getMessage()));
            return response()->json([
                'status'  => false,
                'code'    => 500,
                'message' => 'Server Errors',
                'details' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }

        return parent::render($request, $e);
    }

}
