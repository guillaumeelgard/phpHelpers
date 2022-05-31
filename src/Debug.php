<?php

namespace Bokad\Helpers;

class Debug
{
    private static string $defaultPostMode = 'html';
    private static string $colorHigh = '#00d3d3';
    private static string $colorText = '#bfbfbf';
    private static string $colorBack = '#181818';

    public static function setDefaultPostMode(string $mode): void
    {
        if (!in_array($mode, ['html', 'text'])) {
            throw new \Exception('Invalid mode.');
        }

        self::$defaultPostMode = $mode;
    }

    public static function getColorHigh(): string
    {
        return self::$colorHigh;
    }

    public static function setColorHigh(string $color): void
    {
        self::$colorHigh = $color;
    }

    public static function getColorText(): string
    {
        return self::$colorText;
    }

    public static function setColorText(string $color): void
    {
        self::$colorText = $color;
    }

    public static function getColorBack(): string
    {
        return self::$colorBack;
    }

    public static function setColorBack(string $color): void
    {
        self::$colorBack = $color;
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
