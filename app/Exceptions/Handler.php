<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     * @throws Exception
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $response = null;

        // Get exception type
        switch ($e) {
            case $e instanceof NotFoundException:
                $response = $this->composeJsonResponse(
                    Response::HTTP_NOT_FOUND,
                    $e->getMessage()
                );
                break;
            case $e instanceof UnauthorizedException:
                $response = $this->composeJsonResponse(
                    Response::HTTP_UNAUTHORIZED,
                    $e->getMessage()
                );
                break;
            case $e instanceof AccessDeniedException:
                $response = $this->composeJsonResponse(
                    Response::HTTP_FORBIDDEN,
                    $e->getMessage()
                );
                break;
            case $e instanceof BadRequestException:
                $response = $this->composeJsonResponse(
                    Response::HTTP_BAD_REQUEST,
                    $e->getMessage()
                );
                break;
            case $e instanceof ConflictException:
                $response = $this->composeJsonResponse(
                    Response::HTTP_CONFLICT,
                    $e->getMessage()
                );
                break;
            case $e instanceof InternalServerException:
                $response = $this->composeJsonResponse(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $e->getMessage()
                );
                break;

            case $e instanceof JWTException:
                $response = $this->composeJsonResponse(
                    Response::HTTP_UNAUTHORIZED,
                    "Token not provided"
                );
                break;

            case $e instanceof TokenExpiredException:
                $response = $this->composeJsonResponse(
                    Response::HTTP_UNAUTHORIZED,
                    "Token has expired"
                );
                break;

            case $e instanceof TokenInvalidException:
                $response = $this->composeJsonResponse(
                    Response::HTTP_UNAUTHORIZED,
                    "Invalid Token"
                );
                break;

            default:
                $response = parent::render($request, $e);
        }

        return $response;
    }

    /**
     * Compose http json responses
     *
     * @param  $header
     * @param  $message
     * @return \Illuminate\Http\Response
     */
    private function composeJsonResponse($header, $message)
    {
        return response()->json(["message" => $message], $header);
    }

}
