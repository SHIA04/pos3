<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global CORS handler for CodeIgniter 3.
 * - Sets the appropriate Access-Control-* headers for allowed origins.
 * - Responds to preflight (OPTIONS) requests with 200 and exits early.
 */
class Cors
{
    public function enable()
    {
        // Adjust allowed origins to your needs
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        $allowed_origins = [
            'http://localhost:8080',
            'http://127.0.0.1:8080',
        ];

        if ($origin && in_array($origin, $allowed_origins, true)) {
            header("Access-Control-Allow-Origin: {$origin}");
            header('Vary: Origin'); // ensure caches vary by Origin
            header('Access-Control-Allow-Credentials: true'); // set true only if you need cookies/Authorization
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

            // If the client requests custom headers, echo them back; otherwise provide common defaults
            $requestHeaders = isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])
                ? $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']
                : 'Content-Type, Authorization, X-Requested-With';
            header('Access-Control-Allow-Headers: ' . $requestHeaders);

            // Cache preflight for a day (adjust as needed)
            header('Access-Control-Max-Age: 86400');
        }

        // Short-circuit preflight requests
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}