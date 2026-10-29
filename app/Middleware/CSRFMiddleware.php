<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Security\CSRF;


class CSRFMiddleware
{


    /**
     * Handle CSRF Protection
     */
    public function handle(): void
    {


        $method =
        $_SERVER['REQUEST_METHOD']
        ?? 'GET';

        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $normalizedUri = '/' . ltrim(str_replace('/NRB', '', $requestUri), '/');
        $normalizedUri = preg_replace('#/+#', '/', $normalizedUri) ?: '/';

        if (
            in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)
            && preg_match('#^/check-nid/?$#', $normalizedUri) === 1
        ) {
            return;
        }



        /*
        Only protect changing requests
        */


        if(
            in_array(
                $method,
                [
                    'POST',
                    'PUT',
                    'PATCH',
                    'DELETE'
                ]
            )
        )
        {


            if(
                !CSRF::verify()
            )
            {


                http_response_code(419);


                exit(
                    "CSRF Token Validation Failed"
                );


            }


        }


    }



}