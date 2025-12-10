<?php


class Validation
{
    public static function isEmail($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function sanitizeString($value) {
        return trim(filter_var($value, FILTER_SANITIZE_STRING));
    }

    public static function requireKeys(array $data, array $keys) {
        foreach ($keys as $k) {
            if (!isset($data[$k]) || $data[$k] === '') return false;
        }
        return true;
    }
}
