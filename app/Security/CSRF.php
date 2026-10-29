<?php
declare(strict_types=1);

namespace App\Security;


class CSRF
{


    private static string $key =
    'nrb_csrf_token';





    /**
     * Generate Token
     */
    public static function token(): string
    {


        if(
            empty($_SESSION[self::$key])
        )
        {


            $_SESSION[self::$key] =
            bin2hex(
                random_bytes(32)
            );


        }



        return $_SESSION[self::$key];

    }







    /**
     * Create HTML Input
     */
    public static function field(): string
    {

        return

        '<input type="hidden" name="csrf_token" value="'.

        htmlspecialchars(
            self::token(),
            ENT_QUOTES,
            'UTF-8'
        ).

        '">';

    }








    /**
     * Verify Token
     */
    public static function verify(): bool
    {


        if(
            !isset($_POST['csrf_token'])
        )
        {

            return false;

        }




        return hash_equals(

            $_SESSION[self::$key] ?? '',

            $_POST['csrf_token']

        );


    }







    /**
     * Destroy Token
     */
    public static function destroy(): void
    {

        unset(
            $_SESSION[self::$key]
        );

    }



}
