<?php
declare(strict_types=1);

namespace App\Middleware;


class AuthMiddleware
{


    /**
     * Check User Authentication
     */
    public function handle(): void
    {


        if(
            session_status()
            !== PHP_SESSION_ACTIVE
        )
        {
            session_start();
        }



        if(
            !isset($_SESSION['user'])
        )
        {

            http_response_code(401);

            $loginUrl = function_exists('route_url') ? route_url('/login') : '/login';

            header(
                "Location: " . $loginUrl
            );

            exit;

        }


    }



    /**
     * Check User Role
     */
    public function role(
        string $role
    ): bool
    {


        if(
            !isset($_SESSION['user']['role'])
        )
        {
            return false;
        }



        return
        $_SESSION['user']['role']
        ===
        $role;


    }



}