<?php
declare(strict_types=1);

namespace App\Core;

use Throwable;

class Router
{

    private array $routes = [];

    private array $middleware = [];

    private string $prefix = '';



    /**
     * Register GET Route
     */
    public function get(string $uri, $action): self
    {
        return $this->addRoute(
            'GET',
            $uri,
            $action
        );
    }



    /**
     * Register POST Route
     */
    public function post(string $uri, $action): self
    {
        return $this->addRoute(
            'POST',
            $uri,
            $action
        );
    }




    /**
     * Register PUT Route
     */
    public function put(string $uri, $action): self
    {
        return $this->addRoute(
            'PUT',
            $uri,
            $action
        );
    }




    /**
     * Register DELETE Route
     */
    public function delete(string $uri, $action): self
    {
        return $this->addRoute(
            'DELETE',
            $uri,
            $action
        );
    }





    /**
     * Add Route
     */
    private function addRoute(
        string $method,
        string $uri,
        $action
    ): self
    {

        $uri = $this->prefix . $uri;


        $this->routes[] = [

            'method'=>$method,

            'uri'=>$this->normalize($uri),

            'action'=>$action,

            'middleware'=>$this->middleware

        ];


        return $this;

    }






    /**
     * Dispatch Current Request
     */
    public function dispatch(): void
    {


        $requestMethod =
        $_SERVER['REQUEST_METHOD'] ?? 'GET';



        $requestUri =
        parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        ) ?? '/';

        $baseCandidates = [
            dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'),
            '/public',
            '/NRB/public',
            '/NRB'
        ];

        foreach($baseCandidates as $basePath)
        {
            if(
                $basePath !== '.'
                && $basePath !== '/'
                && (
                    $requestUri === $basePath
                    || str_starts_with($requestUri, $basePath . '/')
                )
            )
            {
                $requestUri =
                substr(
                    $requestUri,
                    strlen($basePath)
                );

                while (
                    $requestUri !== '/'
                    && (
                        $requestUri === '/NRB'
                        || str_starts_with($requestUri, '/NRB/')
                    )
                ) {
                    $requestUri = preg_replace('#^/NRB#', '', $requestUri, 1) ?: '/';
                }

                break;
            }
        }

        if($requestUri === '')
        {
            $requestUri = '/';
        }


        $requestUri =
        $this->normalize($requestUri);



        foreach($this->routes as $route)
        {


            if(
                $route['method'] !== $requestMethod
            )
            {
                continue;
            }



            $parameters =
            $this->match(
                $route['uri'],
                $requestUri
            );



            if($parameters !== false)
            {


                $this->executeMiddleware(
                    $route['middleware']
                );



                $this->executeAction(
                    $route['action'],
                    $parameters
                );


                return;

            }


        }



        $this->notFound();

    }







    /**
     * Match Route Parameters
     */
    private function match(
        string $route,
        string $request
    )
    {


        $routePattern =
        preg_replace(
            '/\{[a-zA-Z0-9_]+\}/',
            '([a-zA-Z0-9\-_]+)',
            $route
        );



        $routePattern =
        "#^".$routePattern."$#";



        if(
            preg_match(
                $routePattern,
                $request,
                $matches
            )
        )
        {


            array_shift($matches);


            return $matches;

        }



        return false;

    }







    /**
     * Execute Controller Action
     */
    private function executeAction(
        $action,
        array $parameters=[]
    ): void
    {


        try {


            /*
            |--------------------------------------------------------------------------
            | Closure Route
            |--------------------------------------------------------------------------
            */

            if(
                $action instanceof \Closure
            )
            {

                call_user_func_array(
                    $action,
                    $parameters
                );


                return;

            }



            /*
            |--------------------------------------------------------------------------
            | Controller Route
            |--------------------------------------------------------------------------
            */

            if(
                is_array($action)
            )
            {


                [
                    $controller,
                    $method
                ] = $action;



                if(
                    !class_exists($controller)
                )
                {

                    throw new \Exception(
                        "Controller not found: ".$controller
                    );

                }



                $instance =
                new $controller();



                if(
                    !method_exists(
                        $instance,
                        $method
                    )
                )
                {

                    throw new \Exception(
                        "Method not found: ".$method
                    );

                }



                call_user_func_array(
                    [
                        $instance,
                        $method
                    ],
                    $parameters
                );



                return;

            }



            throw new \Exception(
                "Invalid route action"
            );



        }
        catch(Throwable $e)
        {

            throw $e;

        }

    }







    /**
     * Execute Middleware
     */
    private function executeMiddleware(
        array $middleware
    ): void
    {


        foreach($middleware as $item)
        {


            if(
                class_exists($item)
            )
            {


                $middlewareObject =
                new $item();



                if(
                    method_exists(
                        $middlewareObject,
                        'handle'
                    )
                )
                {

                    $middlewareObject->handle();

                }


            }

        }

    }







    /**
     * Route Group
     */
    public function group(
        string $prefix,
        callable $callback
    ): void
    {


        $oldPrefix =
        $this->prefix;



        $this->prefix =
        $oldPrefix.$prefix;



        $callback($this);



        $this->prefix =
        $oldPrefix;

    }







    /**
     * Add Route Middleware
     */
    public function middleware(
        string $middleware
    ): self
    {

        $this->middleware[] =
        $middleware;


        return $this;

    }







    /**
     * Normalize URL
     */
    private function normalize(
        string $uri
    ): string
    {


        $uri =
        trim($uri);



        if($uri === '')
        {
            return '/';
        }



        return '/'.trim(
            $uri,
            '/'
        );

    }







    /**
     * 404 Response
     */
    private function notFound(): void
    {


        http_response_code(404);



        if(
            file_exists(
                dirname(__DIR__)
                ."/Views/errors/404.php"
            )
        )
        {

            require dirname(__DIR__)
            ."/Views/errors/404.php";

        }
        else
        {

            echo "404 - Page Not Found";

        }


    }






    /**
     * Get All Routes
     */
    public function routes(): array
    {
        return $this->routes;
    }



}
