<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (AuthorizationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        });

        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Route Not Found',
            ], 404);
        });

        $exceptions->renderable(function (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        });
    })->create();
