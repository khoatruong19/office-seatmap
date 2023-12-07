<?php

namespace core;
class SessionManager
{
    /**
     * @return void
     */
    public static function startSession(): void
    {
        session_start();
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public static function get($key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function exists($key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param $key
     * @return void
     */
    public static function remove($key): void
    {
        if (self::exists($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @return void
     */
    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }
}
