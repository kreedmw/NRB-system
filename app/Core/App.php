<?php
declare(strict_types=1);

namespace App\Core;

use Throwable;

class App
{
    private Router $router;

    private array $middleware = [];

    private bool $running = false;


    /**
     * Application Constructor
     */
    public function __construct()
    {
        $this->initialize();
    }


    /**
     * Initialize Application
     */
    private function initialize(): void
    {
        $this->running = true;

        $this->router = new Router();

        $this->loadMiddleware();

        $this->registerErrorHandling();
    }


    /**
     * Load global middleware
     */
    private function loadMiddleware(): void
    {
        $this->middleware = [

            // Security middleware
            \App\Middleware\SecurityHeadersMiddleware::class,

            // Session validation
            \App\Middleware\SessionMiddleware::class,

            // CSRF protection
            \App\Middleware\CSRFMiddleware::class,

            // Rate limiting
            \App\Middleware\RateLimitMiddleware::class

        ];
    }


    /**
     * Run Application
     */
    public function run(): void
    {

        try {

            $this->executeMiddleware();

            $this->router->dispatch();


        } catch(Throwable $exception) {


            $this->handleException($exception);

        }

    }



    /**
     * Execute Middleware Pipeline
     */
    private function executeMiddleware(): void
    {

        foreach($this->middleware as $middlewareClass)
        {

            if(class_exists($middlewareClass))
            {

                $middleware = new $middlewareClass();


                if(method_exists($middleware,'handle'))
                {

                    $middleware->handle();

                }

            }

        }

    }



    /**
     * Register Global Error Handling
     */
    private function registerErrorHandling(): void
    {

        set_error_handler(function(
            int $severity,
            string $message,
            string $file,
            int $line
        ){

            throw new \ErrorException(
                $message,
                0,
                $severity,
                $file,
                $line
            );

        });



        set_exception_handler(function(Throwable $exception){

            $this->handleException($exception);

        });



        register_shutdown_function(function(){

            $error = error_get_last();


            if($error !== null)
            {

                $this->handleFatalError($error);

            }

        });

    }





    /**
     * Handle Application Exceptions
     */
    private function handleException(Throwable $exception): void
    {

        http_response_code(500);


        $log = sprintf(
            "[%s] %s in %s line %s\n",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );


        $logPath = dirname(__DIR__,2)
            . "/storage/logs/error.log";


        if(is_dir(dirname($logPath)))
        {

            file_put_contents(
                $logPath,
                $log,
                FILE_APPEND
            );

        }



        if(
            isset($_ENV['APP_DEBUG']) &&
            $_ENV['APP_DEBUG'] === "true"
        )
        {

            echo "<pre>";
            echo $exception;
            echo "</pre>";

        }
        else
        {

            require_once dirname(__DIR__)
            . "/Views/errors/500.php";

        }

    }





    /**
     * Handle Fatal PHP Errors
     */
    private function handleFatalError(array $error): void
    {

        if(
            in_array(
                $error['type'],
                [
                    E_ERROR,
                    E_PARSE,
                    E_CORE_ERROR,
                    E_COMPILE_ERROR
                ]
            )
        )
        {


            $logPath = dirname(__DIR__,2)
            . "/storage/logs/fatal.log";


            $message =
            "[".date('Y-m-d H:i:s')."] "
            .$error['message']
            ." File: "
            .$error['file']
            ." Line: "
            .$error['line']
            .PHP_EOL;



            file_put_contents(
                $logPath,
                $message,
                FILE_APPEND
            );

        }

    }





    /**
     * Get Router Instance
     */
    public function router(): Router
    {
        return $this->router;
    }





    /**
     * Add Middleware
     */
    public function middleware(string $middleware): self
    {

        $this->middleware[] = $middleware;

        return $this;

    }





    /**
     * Check Application Status
     */
    public function isRunning(): bool
    {
        return $this->running;
    }



}
 