<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\ValidationException as AppValidationException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Reflector;
use Psr\Log\LoggerInterface;
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

    }

    public function report(Throwable $e)
    {
        $e = $this->mapException($e);

//        if ($this->shouldntReport($e)) {
//            return;
//        }

        if (Reflector::isCallable($reportCallable = [$e, 'report'])) {
            if ($this->container->call($reportCallable) !== false) {
                return;
            }
        }

        foreach ($this->reportCallbacks as $reportCallback) {
            if ($reportCallback->handles($e)) {
                if ($reportCallback($e) === false) {
                    return;
                }
            }
        }

        if ($e instanceof AuthenticationException) {
            $log = [
                'error' => 'Unauthenticated.',
                'message' => trans('general.unauthorized')
            ];
        } elseif ($e instanceof AppValidationException) {
            $log = [
                'status' => 'ERROR',
                'inputErrors' => $e->getValidationErrors()
            ];
        } elseif ($e instanceof NotFoundHttpException) {
            $log = [
                'status' => 'ERROR',
                'message' => trans('general.resource_do_not_exists'),
            ];
        } elseif ($e instanceof BaseException) {
            $log = [
                'status' => 'ERROR',
                'message' => trans($e->getErrorKey())
            ];
        } else {
            $log = [
                'status' => 'ERROR',
                'message' => trans('general.general_error'),
            ];
        }

        try {
            $logger = $this->container->make(LoggerInterface::class);
        } catch (Exception $ex) {
            throw $e;
        }

        $logger->error(
            $e->getMessage(),
            array_merge(
                $this->exceptionContext($e),
                $this->context(),
                ['exception' => $e],
                $log
            )
        );

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
