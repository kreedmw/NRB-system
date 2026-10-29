<?php
declare(strict_types=1);


/*
|--------------------------------------------------------------------------
| National Registration Bureau Web Routes
|--------------------------------------------------------------------------
*/


use App\Controllers\Authentication\LoginController;
use App\Controllers\Authentication\LogoutController;

use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\RoleController;
use App\Controllers\Reports\ReportController;

use App\Controllers\Citizen\CitizenController;
use App\Controllers\Citizen\CheckStatusController;

use App\Controllers\Citizen\BirthController;

use App\Controllers\Citizen\NationalIDController;
use App\Controllers\Workflow\OperationsController;





/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/


$router->get(
    '/',
    [
        LoginController::class,
        'landing'
    ]
);



$router->get(
    '/login',
    [
        LoginController::class,
        'login'
    ]
);


$router->get(
    '/check-nid',
    [
        CheckStatusController::class,
        'check'
    ]
);


$router->post(
    '/check-nid',
    [
        CheckStatusController::class,
        'check'
    ]
);


$router->post(
    '/login',
    [
        LoginController::class,
        'authenticate'
    ]
);



$router->get(
    '/logout',
    [
        LogoutController::class,
        'logout'
    ]
);







/*
|--------------------------------------------------------------------------
| Protected Admin Routes
|--------------------------------------------------------------------------
*/


$router->group(
'/Admin',
function($router)
{


    $router
    ->middleware(
        \App\Middleware\AuthMiddleware::class
    );


    $router->get(
        '/dashboard',
        [
            DashboardController::class,
            'index'
        ]
    );

    $router->get(
        '/users',
        [
            UserController::class,
            'index'
        ]
    );

    $router->get(
        '/roles',
        [
            RoleController::class,
            'index'
        ]
    );

    $router->get(
        '/reports',
        [
            ReportController::class,
            'index'
        ]
    );

});

$rolePaths = [
    'super-admin',
    'national-admin',
    'district-admin',
    'registration-officer',
    'supervisor',
    'card-printing-officer',
    'auditor',
    'read-only-user',
];

foreach ($rolePaths as $rolePath) {
    $router->group('/' . $rolePath, function ($router) {
        $router->middleware(\App\Middleware\AuthMiddleware::class);
        $router->get('/', [DashboardController::class, 'index']);
        $router->get('/dashboard', [DashboardController::class, 'index']);
        $router->get('/profile', [OperationsController::class, 'profileOverview']);
        $router->get('/profile/password', [OperationsController::class, 'profilePassword']);
        $router->get('/profile/activity', [OperationsController::class, 'profileActivity']);
        $router->get('/users', [UserController::class, 'index']);
        $router->get('/roles', [RoleController::class, 'index']);
        $router->get('/reports', [ReportController::class, 'index']);
    });
}








/*
|--------------------------------------------------------------------------
| Citizen Registration Routes
|--------------------------------------------------------------------------
*/


$router->group(
'/citizen',
function($router)
{


    $router
    ->middleware(
        \App\Middleware\AuthMiddleware::class
    );



    /*
    Citizen List
    */


    $router->get(

        '/',

        [
            CitizenController::class,
            'index'
        ]

    );




    /*
    Registration Form
    */


    $router->get(

        '/register',

        [
            CitizenController::class,
            'create'
        ]

    );

    $router->get(

        '/status',

        [
            CitizenController::class,
            'status'
        ]

    );



    /*
    Save Citizen
    */


    $router->post(

        '/register',

        [
            CitizenController::class,
            'store'
        ]

    );



});







/*
|--------------------------------------------------------------------------
| Birth Registration
|--------------------------------------------------------------------------
*/


$router->group(
'/birth',
function($router)
{


    $router
    ->middleware(
        \App\Middleware\AuthMiddleware::class
    );



    $router->get(

        '/register',

        [
            BirthController::class,
            'create'
        ]

    );



    $router->post(

        '/register',

        [
            BirthController::class,
            'store'
        ]

    );


});







/*
|--------------------------------------------------------------------------
| National ID Management
|--------------------------------------------------------------------------
*/


$router->group(
'/national-id',
function($router)
{


    $router
    ->middleware(
        \App\Middleware\AuthMiddleware::class
    );



    $router->get(

        '/search',

        [
            NationalIDController::class,
            'search'
        ]

    );



    $router->post(

        '/generate',

        [
            NationalIDController::class,
            'generate'
        ]

    );



});