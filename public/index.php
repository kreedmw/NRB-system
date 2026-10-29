<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| National Registration Bureau (NRB)
| Front Controller
|--------------------------------------------------------------------------
*/

define('APP_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Error Reporting
|--------------------------------------------------------------------------
*/

ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| Paths
|--------------------------------------------------------------------------
*/

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');

/*
|--------------------------------------------------------------------------
| Load Environment
|--------------------------------------------------------------------------
*/

$envFile = ROOT_PATH . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);

        $_ENV[trim($name)] = trim($value);
    }
}

/*
|--------------------------------------------------------------------------
| Timezone
|--------------------------------------------------------------------------
*/

date_default_timezone_set(
    $_ENV['APP_TIMEZONE'] ?? 'Africa/Blantyre'
);

/*
|--------------------------------------------------------------------------
| Secure Session
|--------------------------------------------------------------------------
*/

session_name('NRB_SESSION');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => (!empty($_SERVER['HTTPS'])),
    'httponly' => true,
    'samesite' => 'Strict'
]);

ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', (!empty($_SERVER['HTTPS'])) ? '1' : '0');
ini_set('session.cookie_samesite', 'Strict');

session_start();

if (!isset($_SESSION['created'])) {

    session_regenerate_id(true);

    $_SESSION['created'] = time();
}

/*
|--------------------------------------------------------------------------
| Security Headers
|--------------------------------------------------------------------------
*/

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
header('Content-Security-Policy: default-src \'self\'; img-src \'self\' data:; style-src \'self\' \'unsafe-inline\'; script-src \'self\' \'unsafe-inline\';');

/*
|--------------------------------------------------------------------------
| Prevent Caching
|--------------------------------------------------------------------------
*/

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

/*
|--------------------------------------------------------------------------
| Composer Autoload
|--------------------------------------------------------------------------
*/

if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
    require ROOT_PATH . '/vendor/autoload.php';
}

require_once APP_PATH . '/Helpers/helpers.php';

/*
|--------------------------------------------------------------------------
| Core Classes
|--------------------------------------------------------------------------
*/

require_once APP_PATH . '/Core/App.php';
require_once APP_PATH . '/Core/Router.php';
require_once APP_PATH . '/Core/Controller.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Core/View.php';
require_once APP_PATH . '/Core/Database.php';

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

require_once CONFIG_PATH . '/app.php';
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/security.php';
require_once CONFIG_PATH . '/session.php';
require_once CONFIG_PATH . '/routes.php';

/*
|--------------------------------------------------------------------------
| Maintenance Mode
|--------------------------------------------------------------------------
*/

if (file_exists(STORAGE_PATH . '/maintenance.lock')) {

    http_response_code(503);

    exit("
    <h1>503 Service Unavailable</h1>
    <p>National Registration Bureau is currently under maintenance.</p>
    ");
}

/*
|--------------------------------------------------------------------------
| Route Request
|--------------------------------------------------------------------------
*/

try {

    $app = new \App\Core\App();
    $router = $app->router();

    require_once ROOT_PATH . '/routes/web.php';
    require_once ROOT_PATH . '/routes/web_workflow_append.php';
    require_once ROOT_PATH . '/routes/admin.php';
    require_once ROOT_PATH . '/routes/api.php';

    $app->run();

} catch (Throwable $e) {

    error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

    http_response_code(500);

    if ($_ENV['APP_DEBUG'] ?? false) {

        echo "<pre>";
        echo $e;
        echo "</pre>";

    } else {

        echo "<h1>500 Internal Server Error</h1>";
    }
}
