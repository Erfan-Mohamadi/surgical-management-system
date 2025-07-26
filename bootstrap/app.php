<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(\App\Http\Middleware\CheckName::class);
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/admin');
        $middleware->alias([
            'doctor_role_sum' => \App\Http\Middleware\EnsureDctorRoleSum::class,
            'check.doctor.unique' => \App\Http\Middleware\CheckDoctorRoleUniqueness::class,
            'check.surgery.date' => \App\Http\Middleware\CheckSurgeryDates::class,

        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->view('errors.404', status: 404);
        });
    })
    ->create();
