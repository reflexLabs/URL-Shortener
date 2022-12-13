<?php

namespace Library\Misc;

class Session {
    public static function flash(string $name, string $string = '') {
        // If the session exists, display it on screen ("flash" it)
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        }

        // The session doesn't exist, set it as a variable now, so it can be "flashed" in the future
        self::put($name, $string);
        return null;
    }

    public static function exists(string $name): bool {
        return isset($_SESSION[$name]);
    }

    public static function get(string $name) {
        return $_SESSION[$name];
    }

    public static function delete(string $name): void {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function put(string $name, string $value): void {
        $_SESSION[$name] = $value;
    }
}
