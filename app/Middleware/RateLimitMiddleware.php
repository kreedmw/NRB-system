<?php
declare(strict_types=1);

namespace App\Middleware;


class RateLimitMiddleware
{


    private int $limit = 100;

    private int $seconds = 60;



    /**
     * Handle Request Rate
     */
    public function handle(): void
    {


        $ip =
        $_SERVER['REMOTE_ADDR']
        ?? 'unknown';



        $file =
        dirname(__DIR__,2)
        .
        "/storage/cache/rate_"
        .
        md5($ip);



        $currentTime=time();



        $requests=[];



        if(
            file_exists($file)
        )
        {


            $requests =
            json_decode(
                file_get_contents($file),
                true
            );


        }



        /*
        Remove expired requests
        */


        $requests =
        array_filter(
            $requests,
            function($time) use ($currentTime)
            {

                return
                ($currentTime-$time)
                <
                $this->seconds;

            }
        );




        if(
            count($requests)
            >=
            $this->limit
        )
        {


            http_response_code(429);


            exit(
                "Too Many Requests"
            );


        }




        $requests[]=$currentTime;



        file_put_contents(

            $file,

            json_encode($requests),

            LOCK_EX

        );



    }



}