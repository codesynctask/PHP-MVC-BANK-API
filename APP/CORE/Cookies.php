<?php

class Cookie
{
    /* Set a cookie */
    public static function set(string $name, string $value,
        int $expiry = 3600,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httponly = true
    ): void {
        setcookie(
            $name,
            $value,
            time() + $expiry,
            $path,
            $domain,
            $secure,
            $httponly
        );
    }

    /* Get a cookie value */
    public static function get(string $name, $default = null)
    {
        return $_COOKIE[$name] ?? $default;
    }

    /* Check if cookie exists */
    public static function has(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    /* Delete a cookie */
    public static function delete( string $name, string $path = '/', string $domain = ''): void {
        setcookie($name, '', time() - 3600, $path, $domain);
        unset($_COOKIE[$name]);
    }
}
