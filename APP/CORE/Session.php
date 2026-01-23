<?php

class Session
{
    /* Start session */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* Set session value */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /* Get session value */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /* Check if session key exists */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /* Remove session key */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /* Destroy full session (logout) */
    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

}
