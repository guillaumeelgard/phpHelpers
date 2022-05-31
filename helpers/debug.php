<?php

use Bokad\Helpers\Debug;

if (!function_exists('explicit_var')) {
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
}

if (!function_exists('pp')) {
    function pp(
        array $varsArray,
        ?int $displayDebugBacktrace = null,
        ?string $tag = null,
        int $offset = 0,
        ?string $mode = null,
    ): void {
        if (is_null($displayDebugBacktrace)) {
            $displayDebugBacktrace = Debug::getDefaultDisplayDebugBacktracePP();
        }

        $preStyle = [
            'background' => Debug::getColorBack(),
            'color' => Debug::getColorText(),
            'padding' => '1em',
            'white-space' => 'break-spaces',
            'border' => 'dashed 1px ' . Debug::getColorHigh(),
        ];

        $hrStyle = [
            'border' => 'none',
            'border-top' => 'dashed 1px ' . Debug::getColorText(),
            'margin' => '2em 0',
        ];

        $tagStyle = [
            'display' => 'inline-block',
            'background' => 'teal',
            'color' => Debug::getColorBack(),
            'padding' => '.5em',
            'font-size' => '1.1em',
        ];

        if (!in_array($mode, ['html', 'text'])) {
            $mode = Debug::getDefaultMode();
        }

        $textLine = '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-';

        switch ($mode) {
            case 'html':
                echo '<pre ' . styleFromArray($preStyle) . '>';
                break;

            case 'text':
                echo "\n";
                echo $textLine;
                echo "\n";
                echo $textLine;
                echo "\n";
                echo "\n";
                break;
        }

        if ($tag) {
            switch ($mode) {
                case 'html':
                    echo '<span ' . styleFromArray($tagStyle) . '>';
                    echo $tag;
                    echo '</span>';
                    break;

                case 'text':
                    echo ' ' . str_pad('', mb_strlen($tag) + 6, '@');
                    echo "\n";
                    echo ' @  ' . str_pad('', mb_strlen($tag), ' ') . '  @';
                    echo "\n";
                    echo ' @  ' . $tag . '  @';
                    echo "\n";
                    echo ' @  ' . str_pad('', mb_strlen($tag), ' ') . '  @';
                    echo "\n";
                    echo ' ' . str_pad('', mb_strlen($tag) + 6, '@');
                    break;
            }

            echo "\n";
            echo "\n";
        }

        if ($displayDebugBacktrace) {
            $display = [];
            $db = debug_backtrace();

            if (!isset($db[$offset])) {
                throw new \Exception('Invalid offset');
            }

            $display[] = $db[$offset]['file'] . ':' . $db[$offset]['line'];
            $display[] = '';

            switch ($displayDebugBacktrace) {
                case 1:
                    if (array_key_exists($offset + 1, $db)) {
                        $dbKeys = [$offset + 1];
                    } else {
                        $dbKeys = [];
                    }
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

            if ($mode == 'html') {
                echo '<span style="color:' . Debug::getColorHigh() . '">';
            }

            echo implode("\n", $display);

            if ($mode == 'html') {
                echo '</span>';
            }

            switch ($mode) {
                case 'html':
                    echo '<hr ' . styleFromArray(array_merge($hrStyle, ['border-top' => 'dashed 1px ' . Debug::getColorHigh()])) . '>';
                    break;

                case 'text':
                    echo "\n";
                    echo $textLine;
                    echo "\n";
                    echo "\n";
                    break;
            }
        }

        $varsArray = array_map(fn ($a) => explicit_var($a), $varsArray);

        switch ($mode) {
            case 'html':
                echo implode('<hr ' . styleFromArray($hrStyle) . '>', $varsArray);
                echo '</pre>';
                break;

            case 'text':
                echo implode("\n\n" . $textLine . "\n\n", $varsArray);
                echo "\n";
                echo $textLine;
                echo "\n";
                echo $textLine;
                echo "\n";
                break;
        }
    }
}

if (!function_exists('p')) {
    function p(mixed ...$vars): void
    {
        pp(func_get_args());
    }
}

if (!function_exists('d')) {
    function d(mixed ...$vars): never
    {
        pp(func_get_args(), Debug::getDefaultDisplayDebugBacktraceDD(), null, 1);
        exit;
    }
}

if (!function_exists('dd')) {
    function dd(
        array $varsArray,
        ?int $displayDebugBacktrace = null,
        ?string $tag = null,
        int $offset = 0,
        ?string $mode = null,
    ): never {
        if (is_null($displayDebugBacktrace)) {
            $displayDebugBacktrace = Debug::getDefaultDisplayDebugBacktraceDD();
        }

        pp($varsArray, $displayDebugBacktrace, $tag, $offset + 1, $mode);
        exit;
    }
}
