<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            $payload = [
                'error'   => class_basename($e),
                'message' => $e->getMessage(),
            ];

            if (config('app.debug')) {
                $payload['file']  = $e->getFile();
                $payload['line']  = $e->getLine();
                $payload['trace'] = collect($e->getTrace())->take(5);
            }

            return response()->json($payload, $status);
        }

        return parent::render($request, $e);
    }
}
