<?php
declare(strict_types=1);

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        if ($cache === []) {
            foreach (['app', 'database', 'security', 'session'] as $name) {
                $file = dirname(__DIR__, 1) . '/../config/' . $name . '.php';
                if (is_file($file)) {
                    $cache[$name] = require $file;
                }
            }
        }

        $segments = explode('.', $key);
        $value = $cache[$segments[0]] ?? [];

        foreach (array_slice($segments, 1) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value ?? $default;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(App\Security\CSRF::token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('app_base_path')) {
    function app_base_path(): string
    {
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '/index.php');
        $base = rtrim(dirname($script), '/');

        if ($base === '/public' || $base === '/NRB/public') {
            $base = dirname($base);
        }

        if ($base === '/NRB/public') {
            $base = '/NRB';
        }

        if ($base === '/' || $base === '' || $base === '.') {
            return '';
        }

        return $base;
    }
}

if (!function_exists('normalize_relative_path')) {
    function normalize_relative_path(string $path): string
    {
        $normalized = '/' . ltrim((string) $path, '/');
        $normalized = preg_replace('#/+#', '/', $normalized) ?: '/';

        while (preg_match('#^/(?:NRB)(?=/|$)#i', $normalized) === 1) {
            $normalized = preg_replace('#^/(?:NRB)(?=/|$)#i', '', $normalized, 1) ?: '/';
        }

        $base = rtrim((string) app_base_path(), '/');
        if ($base !== '') {
            while (preg_match('#^' . preg_quote($base, '#') . '(?=/|$)#i', $normalized) === 1) {
                $normalized = preg_replace('#^' . preg_quote($base, '#') . '(?=/|$)#i', '', $normalized, 1) ?: '/';
            }
        }

        $normalized = '/' . ltrim($normalized, '/');

        return $normalized === '' ? '/' : $normalized;
    }
}

if (!function_exists('route_url')) {
    function route_url(string $path = '/'): string
    {
        $base = rtrim((string) app_base_path(), '/');
        $normalized = normalize_relative_path($path);

        if ($normalized === '/') {
            return $base === '' ? '/' : $base;
        }

        return $base === '' ? $normalized : $base . $normalized;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header('Location: ' . route_url($url));
        exit;
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $base = rtrim((string) app_base_path(), '/');
        $cleanPath = normalize_relative_path($path);

        $fullBase = $scheme . '://' . $host . ($base === '' ? '' : $base);

        if ($cleanPath === '/') {
            return $fullBase;
        }

        return rtrim($fullBase, '/') . $cleanPath;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return base_url('public/' . ltrim($path, '/'));
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return $_POST[$key] ?? $_SESSION['old'][$key] ?? $default;
    }
}
