<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;


class Database
{


    private static ?PDO $connection=null;



    /**
     * Create Connection
     */
    public static function connection(): PDO
    {


        if(self::$connection)
        {
            return self::$connection;
        }



        try{


            $host =
            $_ENV['DB_HOST']
            ?? 'localhost';


            $database =
            $_ENV['DB_DATABASE']
            ?? 'nrb';



            $username =
            $_ENV['DB_USERNAME']
            ?? 'root';



            $password =
            $_ENV['DB_PASSWORD']
            ?? '';



            $dsn =
            "mysql:host=$host;
            dbname=$database;
            charset=utf8mb4";



            self::$connection =
            new PDO(
                $dsn,
                $username,
                $password,
                [

                PDO::ATTR_ERRMODE =>
                PDO::ERRMODE_EXCEPTION,


                PDO::ATTR_DEFAULT_FETCH_MODE =>
                PDO::FETCH_ASSOC,


                PDO::ATTR_EMULATE_PREPARES =>
                false

                ]
            );



            return self::$connection;


        }
        catch(PDOException $e)
        {


            error_log(
                $e->getMessage()
            );


            die(
                "Database connection failed"
            );


        }


    }




    /**
     * Prevent Clone
     */
    private function __clone(){}



}
