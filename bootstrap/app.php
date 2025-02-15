<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\TokenChecker;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
        $middleware->api([
            \Rakutentech\LaravelRequestDocs\LaravelRequestDocsMiddleware::class,
        ]);
        $middleware->alias([
            'tokenChecker' => TokenChecker::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Exception $e, Request $request) {
            if ($request->expectsJson()) {
                if ($e instanceof AuthenticationException) {
                    return messageResponse("Sorry, You aren't authenticated", 401);
                } elseif ($e instanceof TooManyRequestsHttpException) {
                    return messageResponse('Too many attempts, please slow down the request.', $e->getStatusCode(), 'serverError');
                } elseif ($e instanceof AccessDeniedHttpException) {
                    return messageResponse('Sorry, You are unauthorized...', $e->getStatusCode());
                } elseif ($e instanceof NotFoundHttpException) {
                    return messageResponse('Route not found...');
                }
            }
        });
    })->create();
