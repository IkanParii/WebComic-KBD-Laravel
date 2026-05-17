<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $isLocal = app()->environment('local');

        $scriptSrc = [
            "'self'",
            "'unsafe-inline'",
            'https://www.google.com/recaptcha/',
            'https://www.gstatic.com/recaptcha/',
        ];

        $styleSrc = [
            "'self'",
            "'unsafe-inline'",
            'https://fonts.googleapis.com',
        ];

        $connectSrc = ["'self'"];

        if ($isLocal) {
            $scriptSrc[] = "'unsafe-eval'";
            $scriptSrc[] = 'http://127.0.0.1:5173';
            $scriptSrc[] = 'http://localhost:5173';
            $styleSrc[] = 'http://127.0.0.1:5173';
            $styleSrc[] = 'http://localhost:5173';
            $connectSrc[] = 'http://127.0.0.1:5173';
            $connectSrc[] = 'http://localhost:5173';
            $connectSrc[] = 'ws://127.0.0.1:5173';
            $connectSrc[] = 'ws://localhost:5173';
        }

        if ((bool) env('SECURITY_ENABLE_CSP', false)) {
            $directives = [
                "default-src 'self'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'none'",
                "object-src 'none'",
                'script-src '.implode(' ', $scriptSrc),
                'style-src '.implode(' ', $styleSrc),
                "font-src 'self' https://fonts.gstatic.com data:",
                "img-src 'self' data: https:",
                "frame-src 'self' https://www.google.com/recaptcha/",
                'connect-src '.implode(' ', $connectSrc),
            ];

            $csp = implode('; ', $directives);
            $response->headers->set('Content-Security-Policy', $csp);
        }
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
