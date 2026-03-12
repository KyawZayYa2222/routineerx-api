<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle ModelNotFound (404)
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage() ?: 'Resource not found.',
                'errors' => []
            ], 404);
        });

        // Handle Validation Errors (422)
        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage() ?: 'The given data was invalid.',
                'errors' => $e->errors()
            ], $e->status);
        });

        // Handle Generic HTTP Exceptions (403, 404, 500, etc.)
        $exceptions->render(function (HttpException $e, $request) {
            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage() ?: 'An error occurred.',
                'errors' => []
            ], $e->getStatusCode());
        });

        // Handle authentication exceptions (401)
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Unauthenticated.',
                    'errors' => []
                ], 401);
            }
        });
    })->create();
