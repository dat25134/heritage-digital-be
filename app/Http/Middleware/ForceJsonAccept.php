<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonAccept
{
    public function handle(Request $request, Closure $next): Response
    {
        // Force clients to negotiate JSON
        $request->headers->set('Accept', 'application/json');

        // Ensure JSON content-type for requests with a body when header is missing
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true)
            && !$request->headers->has('Content-Type')) {
            $request->headers->set('Content-Type', 'application/json');
        }

        /** @var Response $response */
        $response = $next($request);

        // Normalize response content type to JSON
        if (!$response->headers->has('Content-Type')) {
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}


