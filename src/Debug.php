<?php

namespace Bokad\Helpers;

class Debug
{
    private static string $defaultPostMode = 'html';

    public static function getDefaultPostMode(): string
    {
        return self::$defaultPostMode;
    }

    public static function setDefaultPostMode(string $mode): void
    {
        if (!in_array($mode, ['html', 'text'])) {
            throw new \Exception('Invalid mode.');
        }

        self::$defaultPostMode = $mode;
    }

    public static function getDefaultMode(): string
    {
        if (substr(php_sapi_name(), 0, 3) == 'cli') {
            return 'text';
        }

        if (is_ajax()) {
            return 'text';
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return self::$defaultPostMode;
        }

        return 'html';
    }
}
