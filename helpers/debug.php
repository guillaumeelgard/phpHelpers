<?php

namespace Bokad\Helpers\Debug;

use function Bokad\Helpers\Array\styleFromArray;

function explicit_var(mixed $var): string
{
    switch (gettype($var)) {
        case 'object':
        case 'array':
            return print_r($var, true);

        case 'string':
            return '(string ' . mb_strlen($var) . ') "' . $var . '"';

        case 'integer':
        case 'double':
            return '(' . gettype($var) . ') ' . $var;

        case 'boolean':
            return '(boolean) ' . ($var ? 'true' : 'false');

        case 'NULL':
            return 'NULL';

        default:
            throw new \Exception('Type ' . gettype($var) . ' non géré.');
    }
}

function pp(array $varsArray, int $displayDebugBacktrace = 0, ?string $tag = null, int $offset = 0): void
{
    $preStyle = [
        'background' => '#181818',
        'color' => '#8c8c8c',
        'padding' => '1em',
        'white-space' => 'break-spaces',
        'border' => 'dashed 1px teal',
    ];

    $hrStyle = [
        'border' => 'none',
        'border-top' => 'dashed 1px #8c8c8c',
        'margin' => '2em 0',
    ];

    $tagStyle = [
        'display' => 'inline-block',
        'background' => 'teal',
        'color' => '#181818',
        'padding' => '.5em',
        'font-size' => '1.1em',
    ];

    echo '<pre ' . styleFromArray($preStyle) . '>';

    if ($tag) {
        echo '<span ' . styleFromArray($tagStyle) . '>';
        echo $tag;
        echo '</span>';
        echo "\n";
        echo "\n";
    }

    if ($displayDebugBacktrace) {
        $display = [];

        $db = debug_backtrace();
        $debug_backtrace_1 = $db[$offset];
        $display[] = $debug_backtrace_1['file'] . ':' . $debug_backtrace_1['line'];
        $display[] = '';
        switch ($displayDebugBacktrace) {
            case 1:
                $dbKeys = [$offset + 1];
                break;

            case 2:
                $dbKeys = range($offset + 1, count($db) - 1);
                break;

            default:
                throw new \Exception('Invalid $displayDebugBacktrace value');
        }

        foreach ($dbKeys as $k) {
            $display[] = $db[$k]['file'] . ':' . $db[$k]['line'];
            $display[] = (array_key_exists('class', $db[$k]) ? $db[$k]['class'] . '::' : '') . $db[$k]['function'] . '()';
            $display[] = '';
        }

        echo '<span style="color:teal">';
        echo implode("\n", $display);
        echo '</span>';
        echo '<hr ' . styleFromArray(array_merge($hrStyle, ['border-top' => 'dashed 1px teal'])) . '>';
    }
    echo implode('<hr ' . styleFromArray($hrStyle) . '>', array_map(fn ($a) => explicit_var($a), $varsArray));
    echo '</pre>';
}

function p(mixed ...$vars): void
{
    pp(func_get_args());
}

function d(mixed ...$vars): never
{
    pp(func_get_args(), true, null, 1);
    exit;
}

function dd(array $varsArray, int $displayDebugBacktrace = 0, ?string $tag = null, int $offset = 0): never
{
    pp($varsArray, $displayDebugBacktrace, $tag, $offset);
    exit;
}
