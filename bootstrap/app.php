<?php

use App\Helpers\CreateResponseMessage;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->statefulApi();

        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        if (!env("APP_DEBUG"))
            $exceptions->respond(function (Response $response) {

                if (!request()->expectsJson()) {
                    abort(403);
                } else return response()->json(CreateResponseMessage::Error('error_base', json_decode((json_encode(["error" => $response->getStatusCode()])))), $response->getStatusCode());
            });
    })->create();
