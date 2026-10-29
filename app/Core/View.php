<?php
declare(strict_types=1);

namespace App\Core;


class View
{


    public static function render(
        string $view,
        array $data=[]
    ): void
    {


        $file =
        dirname(__DIR__)
        ."/Views/"
        .$view
        .".php";



        if(!file_exists($file))
        {

            http_response_code(500);

            exit(
                "View not found"
            );

        }



        extract($data);



        require $file;

    }





    /**
     * Escape HTML Output
     */
    public static function escape(
        string $value
    ): string
    {

        return htmlspecialchars(
            $value,
            ENT_QUOTES,
            'UTF-8'
        );

    }



}
