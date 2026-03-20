<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Allow the frontend dev server or other clients to POST to this search endpoint
        'advocate',
        'advocate/*',
        'advocate/search',
        // Also exempt the API-prefixed routes
        'api/advocate',
        'api/advocate/*',
        'api/advocate/search',
    ];
}
