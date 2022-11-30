<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\ValidationException as AppValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response
     *
     */
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'error' => 'Unauthenticated.',
                'message' => trans('general.unauthorized')
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        if ($e instanceof AppValidationException) {
            return response()->json([
                'status' => 'ERROR',
                'inputErrors' => $e->getValidationErrors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'status' => 'ERROR',
                'message' => trans('general.resource_do_not_exists'),
                'exception' => app()->environment('prod') ? [] :
                    [
                        'url' => $request->fullUrl(),
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                    ]
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        if ($e instanceof BaseException) {
            return response()->json([
                'status' => 'ERROR',
                'message' => trans($e->getErrorKey()),
                'exception' => app()->environment('prod') ? [] :
                    [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                        'trace' => $e->getTrace(),
                    ]
            ], 400, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json([
            'status' => 'ERROR',
            'message' => trans('general.general_error'),
            'exception' => app()->environment('prod') ? [] :
                [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    //'trace' => $e->getTrace(),//641
                ]
        ], 500, [], JSON_UNESCAPED_UNICODE);
    }
}
