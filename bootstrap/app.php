<?php
use App\Http\Middleware\CheckPublisher;
use App\Http\Middleware\EnsurePublisherOtpVerified;
use App\Http\Middleware\InactivityLogout;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            SecurityHeaders::class,
            InactivityLogout::class,
        ]);
        $middleware->alias([
            'publisher'          => CheckPublisher::class,
            'publisher.otp'      => EnsurePublisherOtpVerified::class,
            'publisher.verify'   => \App\Http\Middleware\PublisherVerificationMiddleware::class,
            'admin'              => \App\Http\Middleware\AdminMiddleware::class,
            'inactivity.logout'  => InactivityLogout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

