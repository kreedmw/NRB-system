<?php
declare(strict_types=1);

namespace App\Security;


class Session
{


    /**
     * Start Secure Session
     */
    public static function start(): void
    {


        if(session_status()
        === PHP_SESSION_ACTIVE)
        {
            return;
        }



        session_name(
            'NRB_SESSION'
        );



        session_set_cookie_params([

            'lifetime'=>0,

            'path'=>'/',

            'secure'=>
            isset($_SERVER['HTTPS']),


            'httponly'=>true,


            'samesite'=>'Strict'

        ]);



        ini_set(
            'session.use_only_cookies',
            '1'
        );


        ini_set(
            'session.use_strict_mode',
            '1'
        );



        session_start();



        self::regenerate();

    }






    /**
     * Prevent Session Fixation
     */
    public static function regenerate(): void
    {


        if(
            !isset($_SESSION['initialized'])
        )
        {

            session_regenerate_id(true);


            $_SESSION['initialized']=true;

        }


    }






    /**
     * Destroy Session
     */
    public static function destroy(): void
    {


        $_SESSION=[];



        if(
            ini_get("session.use_cookies")
        )
        {


            $params =
            session_get_cookie_params();


            setcookie(

                session_name(),

                '',

                time()-42000,

                $params["path"],

                $params["domain"],

                $params["secure"],

                $params["httponly"]

            );

        }



        session_destroy();


    }







    /**
     * Store Session Data
     */
    public static function set(
        string $key,
        mixed $value
    ): void
    {

        $_SESSION[$key]=$value;

    }







    /**
     * Get Session Data
     */
    public static function get(
        string $key,
        mixed $default=null
    ): mixed
    {

        return $_SESSION[$key] ?? $default;

    }







    /**
     * Remove Data
     */
    public static function remove(
        string $key
    ): void
    {

        unset($_SESSION[$key]);

    }


}
