<?php

namespace App\Exceptions;

use App\Library\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param $request
     * @param Throwable $exception
     * @return Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            $errors = $e->validator->errors()->all();
            return Response::Error($errors, 422);
        }
        return Response::Error($e->getMessage(), 401);
    }
}
