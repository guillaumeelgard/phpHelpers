<?php

namespace Bokad\Helpers;

class Debug
{
    private static string $defaultPostMode = 'html';
    private static string $colorHigh = '#00d3d3';
    private static string $colorText = '#bfbfbf';
    private static string $colorBack = '#181818';
    private static int $defaultDisplayDebugBacktracePP = 0;
    private static int $defaultDisplayDebugBacktraceD = 1;
    private static int $defaultDisplayDebugBacktraceDD = 2;

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

    public static function getDefaultDisplayDebugBacktracePP(): int
    {
        return self::$defaultDisplayDebugBacktracePP;
    }

    public static function setDefaultDisplayDebugBacktracePP(int $value): void
    {
        if (!in_array($value, [0, 1, 2])) {
            throw new \Exception('Invalid value.');
        }

        self::$defaultDisplayDebugBacktracePP = $value;
    }

    public static function getDefaultDisplayDebugBacktraceD(): int
    {
        return self::$defaultDisplayDebugBacktraceD;
    }

    public static function setDefaultDisplayDebugBacktraceD(int $value): void
    {
        if (!in_array($value, [0, 1, 2])) {
            throw new \Exception('Invalid value.');
        }

        self::$defaultDisplayDebugBacktraceD = $value;
    }

    public static function getDefaultDisplayDebugBacktraceDD(): int
    {
        return self::$defaultDisplayDebugBacktraceDD;
    }

    public static function setDefaultDisplayDebugBacktraceDD(int $value): void
    {
        if (!in_array($value, [0, 1, 2])) {
            throw new \Exception('Invalid value.');
        }

        self::$defaultDisplayDebugBacktraceDD = $value;
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
