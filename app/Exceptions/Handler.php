<?php

declare(strict_types=1);

namespace Morris\Core\Exceptions;

use Error;
use Exception;
use Morris\Core\Http\Responses\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];
    protected $dontReport = [];
    protected $dontFlash = [
        "current_password",
        "password",
        "password_confirmation",
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $exception, $request) {
            $apiResponse = app(ApiResponse::class);

            if ($request->is("api/*")) {
                if ($exception instanceof AuthenticationException) {
                    return $apiResponse->unauthorizedAccess()->create();
                }

                if ($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException) {
                    return $apiResponse->forbiddenAccess()->create();
                }

                if ($exception instanceof ValidationException) {
                    return $apiResponse->validationFail($exception->getMessage())
                        ->addErrors($exception->errors())
                        ->create();
                }

                if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                    return $apiResponse->notFound()->create();
                }

                if ($exception instanceof HttpException) {
                    return $apiResponse->setCode($exception->getStatusCode())
                        ->addMessage(empty($exception->getMessage()) ? trans("responses.general_error") : $exception->getMessage())
                        ->addHeaders($exception->getHeaders())
                        ->create();
                }

                if ($exception instanceof Exception || $exception instanceof Error) {
                    return app(ApiResponse::class)
                        ->serverError($this->formatException($exception, trans("responses.server_error")))
                        ->create();
                }
            }
        });
    }

    public function formatException($e, ?string $message = null): string
    {
        if (!config("app.debug") && !App::runningUnitTests()) {
            return $message === null ? $e->getMessage() : $message;
        }

        if (!$e instanceof Throwable) {
            return "[object] (" . get_class($e) . "):" . $e->getMessage() . '\n' .
                $e->getTraceAsString()
                ;
        }

        $previousText = "";
        if ($previous = $e->getPrevious()) {
            do {
                $previousText .= ", " . get_class($previous) .
                    "(code: " . $previous->getCode() . "): " .
                    $previous->getMessage() . " at " . $previous->getFile() . ":" .
                    $previous->getLine();
            } while ($previous = $previous->getPrevious());
        }

        return "Exception: (" . get_class($e) . "(code: " . $e->getCode() . "): " .
            $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . '\n' .
            $previousText . '\n' .
            $this->getExceptionTraceAsString($e)
            ;
    }

    protected function getExceptionTraceAsString($exception): string
    {
        $trace = "";
        $count = 0;
        foreach ($exception->getTrace() as $frame) {
            $args = "";
            if (array_key_exists("args", $frame)) {
                $args = [];
                foreach ($frame["args"] as $arg) {
                    if (is_string($arg)) {
                        $args[] = "'" . $arg . "'";
                    } elseif (is_array($arg)) {
                        $args[] = "Array";
                    } elseif ($arg === null) {
                        $args[] = "NULL";
                    } elseif (is_bool($arg)) {
                        $args[] = $arg ? "true" : "false";
                    } elseif (is_object($arg)) {
                        $args[] = get_class($arg);
                    } elseif (is_resource($arg)) {
                        $args[] = get_resource_type($arg);
                    } else {
                        $args[] = $arg;
                    }
                }
                $args = join(", ", $args);
            }

            $trace .= sprintf(
                '#%s %s(%s): %s(%s)\n',
                $count,
                array_key_exists("file", $frame) ? $frame["file"] : "?",
                array_key_exists("line", $frame) ? $frame["line"] : "?",
                array_key_exists("function", $frame) ? $frame["function"] : "?",
                $args,
            );
            $count++;
        }
        return $trace;
    }
}
