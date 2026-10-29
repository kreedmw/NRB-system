<?php
declare(strict_types=1);

namespace App\Core;

use App\Security\CSRF;

class Controller
{

    protected array $data = [];


    /**
     * Load View
     */
    protected function view(
        string $view,
        array $data = []
    ): void
    {

        View::render(
            $view,
            $data
        );

    }



    /**
     * Return JSON Response
     */
    protected function json(
        array $data,
        int $status = 200
    ): void
    {

        http_response_code($status);

        header(
            "Content-Type: application/json"
        );


        echo json_encode(
            $data,
            JSON_PRETTY_PRINT
        );

        exit;

    }




    /**
     * Redirect User
     */
    protected function redirect(
        string $url
    ): void
    {

        $target = $url;

        if (function_exists('route_url')) {
            $target = route_url($url);
        }

        header(
            "Location: ".$target
        );

        exit;

    }





    /**
     * Get POST Data
     */
    protected function input(
        string $key,
        $default=null
    )
    {

        return $_POST[$key] ?? $default;

    }





    /**
     * Validate CSRF
     */
    protected function csrfCheck(): bool
    {

        if(class_exists(CSRF::class))
        {

            return CSRF::verify();

        }


        return true;

    }




    /**
     * Current User
     */
    protected function user()
    {

        return $_SESSION['user'] ?? null;

    }




    /**
     * Return Error
     */
    protected function error(
        string $message
    ): void
    {

        $_SESSION['error']=$message;

    }



    /**
     * Return Success
     */
    protected function success(
        string $message
    ): void
    {

        $_SESSION['success']=$message;

    }


}
