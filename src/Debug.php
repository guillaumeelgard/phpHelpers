<?php

namespace Bokad\Helpers;

class Debug
{
    private const defaultPostMode = 'html';
    private static string $postMode;

    private const defaultColorBack = '#181818';
    private const defaultColorText = '#bfbfbf';
    private const defaultColorHigh = '#00d3d3';
    private static string $colorBack;
    private static string $colorText;
    private static string $colorHigh;
    private const colorList = ['black','silver','gray','white','maroon','red','purple','fuchsia','green','lime','olive','yellow','navy','blue','teal','aqua','aliceblue','antiquewhite','aqua','aquamarine','azure','beige','bisque','black','blanchedalmond','blue','blueviolet','brown','burlywood','cadetblue','chartreuse','chocolate','coral','cornflowerblue','cornsilk','crimson','cyan','darkblue','darkcyan','darkgoldenrod','darkgray','darkgreen','darkgrey','darkkhaki','darkmagenta','darkolivegreen','darkorange','darkorchid','darkred','darksalmon','darkseagreen','darkslateblue','darkslategray','darkslategrey','darkturquoise','darkviolet','deeppink','deepskyblue','dimgray','dimgrey','dodgerblue','firebrick','floralwhite','forestgreen','fuchsia','gainsboro','ghostwhite','gold','goldenrod','gray','green','greenyellow','grey','honeydew','hotpink','indianred','indigo','ivory','khaki','lavender','lavenderblush','lawngreen','lemonchiffon','lightblue','lightcoral','lightcyan','lightgoldenrodyellow','lightgray','lightgreen','lightgrey','lightpink','lightsalmon','lightseagreen','lightskyblue','lightslategray','lightslategrey','lightsteelblue','lightyellow','lime','limegreen','linen','magenta','maroon','mediumaquamarine','mediumblue','mediumorchid','mediumpurple','mediumseagreen','mediumslateblue','mediumspringgreen','mediumturquoise','mediumvioletred','midnightblue','mintcream','mistyrose','moccasin','navajowhite','navy','oldlace','olive','olivedrab','orange','orangered','orchid','palegoldenrod','palegreen','paleturquoise','palevioletred','papayawhip','peachpuff','peru','pink','plum','powderblue','purple','red','rosybrown','royalblue','saddlebrown','salmon','sandybrown','seagreen','seashell','sienna','silver','skyblue','slateblue','slategray','slategrey','snow','springgreen','steelblue','tan','teal','thistle','tomato','turquoise','violet','wheat','white','whitesmoke','yellow','yellowgreen'];

    private const defaultDisplayDebugBacktracePP = 0;
    private const defaultDisplayDebugBacktraceD = 1;
    private const defaultDisplayDebugBacktraceDD = 1;
    private static int $displayDebugBacktracePP;
    private static int $displayDebugBacktraceD;
    private static int $displayDebugBacktraceDD;

    private static int $offset = 0;

    private static function verifyMode(mixed $mode): bool
    {
        return in_array($mode, ['html', 'text']);
    }

    public static function setPostMode(string $mode): void
    {
        if (!self::verifyMode($mode)) {
            throw new \Exception('Invalid mode.');
        }

        self::$postMode = $mode;
    }

    public static function moreOffset(): void
    {
        static::$offset++;
    }

    private static function verifyColor(mixed $color): bool
    {
        return
            is_string($color)
            &&
            (
                preg_match('/^\#[0-9a-fA-F]{3}$/', $color)
                ||
                preg_match('/^\#[0-9a-fA-F]{4}$/', $color)
                ||
                preg_match('/^\#[0-9a-fA-F]{6}$/', $color)
                ||
                preg_match('/^\#[0-9a-fA-F]{8}$/', $color)
                ||
                in_array(mb_strtolower($color), static::colorList)
            );
    }

    public static function setColorBack(string $color): void
    {
        if (!self::verifyColor($color)) {
            throw new \Exception('Invalid color.');
        }

        self::$colorBack = $color;
    }

    private static function getColorBack(): string
    {
        return self::$colorBack ?? self::defaultColorBack;
    }

    public static function setColorText(string $color): void
    {
        if (!self::verifyColor($color)) {
            throw new \Exception('Invalid color.');
        }

        self::$colorText = $color;
    }

    private static function getColorText(): string
    {
        return self::$colorText ?? self::defaultColorText;
    }

    public static function setColorHigh(string $color): void
    {
        if (!self::verifyColor($color)) {
            throw new \Exception('Invalid color.');
        }

        self::$colorHigh = $color;
    }

    private static function getColorHigh(): string
    {
        return self::$colorHigh ?? self::defaultColorHigh;
    }

    private static function verifyDisplayDebugBacktrace(mixed $debugBacktrace): bool
    {
        return in_array($debugBacktrace, [0, 1, 2]);
    }

    public static function setDisplayDebugBacktracePP(int $value): void
    {
        if (!self::verifyDisplayDebugBacktrace($value)) {
            throw new \Exception('Invalid displayDebugBacktrace value.');
        }

        self::$displayDebugBacktracePP = $value;
    }

    private static function getDisplayDebugBacktracePP(): int
    {
        return self::$displayDebugBacktracePP ?? self::defaultDisplayDebugBacktracePP;
    }

    public static function setDisplayDebugBacktraceD(int $value): void
    {
        if (!self::verifyDisplayDebugBacktrace($value)) {
            throw new \Exception('Invalid displayDebugBacktrace value.');
        }

        self::$displayDebugBacktraceD = $value;
    }

    private static function getDisplayDebugBacktraceD(): int
    {
        return self::$displayDebugBacktraceD ?? self::defaultDisplayDebugBacktraceD;
    }

    public static function setDisplayDebugBacktraceDD(int $value): void
    {
        if (!self::verifyDisplayDebugBacktrace($value)) {
            throw new \Exception('Invalid displayDebugBacktrace value.');
        }

        self::$displayDebugBacktraceDD = $value;
    }

    private static function getDisplayDebugBacktraceDD(): int
    {
        return self::$displayDebugBacktraceDD ?? self::defaultDisplayDebugBacktraceDD;
    }

    private static function getMode(): string
    {
        if (substr(php_sapi_name(), 0, 3) == 'cli') {
            return 'text';
        }

        if (is_ajax()) {
            return 'text';
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return self::$postMode ?? self::defaultPostMode;
        }

        return 'html';
    }

    private static function getDisplayDebugBacktrace(int $displayDebugBacktrace, int $offset): array
    {
        if ($displayDebugBacktrace == 0) {
            return [];
        }

        $display = [];
        $db = debug_backtrace();
        $offset++;

        if (!isset($db[$offset])) {
            throw new \Exception('Invalid offset');
        }

        switch ($displayDebugBacktrace) {
            case 1:
                $dbKeys = [$offset];
                break;

            case 2:
                $dbKeys = range($offset, count($db) - 1);
                break;

            default:
                throw new \Exception('Invalid $displayDebugBacktrace value');
        }

        foreach ($dbKeys as $k) {
            if (isset($db[$k]['file'])) {
                $display[] = $db[$k]['file'] . ':' . $db[$k]['line'];
            }
            $display[] = (array_key_exists('class', $db[$k]) ? $db[$k]['class'] . '::' : '') . $db[$k]['function'] . '()';
            $display[] = '';
        }

        return $display;
    }

    public static function explicit_var(mixed $var): string
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

    private static function verifyTag(mixed $tag): bool
    {
        return is_scalar($tag) && !is_bool($tag);
    }

    private static function verifyOffset(mixed $offset): bool
    {
        return is_int($offset) && $offset > -1;
    }

    public static function p(mixed ...$vars): void
    {
        self::moreOffset();
        self::pp(func_get_args());
    }

    public static function pp(array $vars = [], array $options = []): void
    {
        $mode = isset($options['mode']) && self::verifyMode($options['mode']) ? $options['mode'] : self::getMode();
        $tag = isset($options['tag']) && self::verifyTag($options['tag']) ? $options['tag'] : null;

        if (isset($options['backtrace']) && is_string($options['backtrace'])) {
            $backtrace = [htmlspecialchars($options['backtrace'])];
        } else {
            $displayDebugBacktrace = isset($options['displayDebugBacktrace']) && self::verifyDisplayDebugBacktrace($options['displayDebugBacktrace']) ? $options['displayDebugBacktrace'] : self::getDisplayDebugBacktracePP();
            $offset = isset($options['offset']) && self::verifyOffset($options['offset']) ? $options['offset'] : 0;
            $offset+= self::$offset + 1;
            self::$offset = 0;
            $backtrace = self::getDisplayDebugBacktrace($displayDebugBacktrace, $offset);
        }

        if (!isset($options['explicit_var']) || $options['explicit_var']) {
            $vars = array_map(fn ($a) => Debug::explicit_var($a), $vars);
        }

        switch ($mode) {
            case 'html':
                $colorBack = isset($options['colorBack']) && self::verifyColor($options['colorBack']) ? $options['colorBack'] : self::getColorBack();
                $colorText = isset($options['colorText']) && self::verifyColor($options['colorText']) ? $options['colorText'] : self::getColorText();
                $colorHigh = isset($options['colorHigh']) && self::verifyColor($options['colorHigh']) ? $options['colorHigh'] : self::getColorHigh();
                $margin = isset($options['margin']) ? (bool) $options['margin'] : true;
                $border = isset($options['border']) ? (bool) $options['border'] : true;
                self::ppHtml($vars, $tag, $backtrace, $colorBack, $colorText, $colorHigh, $margin, $border);
                break;

            case 'text':
                self::ppText($vars, $tag, $backtrace);
                break;
        }
    }

    public static function d(mixed ...$vars): never
    {
        self::moreOffset();
        self::pp(func_get_args(), [
            'displayDebugBacktrace' => self::getDisplayDebugBacktraceD(),
        ]);
        exit;
    }

    public static function dd(array $vars = [], array $options = []): never
    {
        $options = array_merge(
            [
                'displayDebugBacktrace' => self::getDisplayDebugBacktraceDD(),
            ],
            $options
        );

        if (!static::verifyDisplayDebugBacktrace($options['displayDebugBacktrace'])) {
            $options['displayDebugBacktrace'] = self::getDisplayDebugBacktraceDD();
        }

        self::moreOffset();
        self::pp($vars, $options);
        exit;
    }

    private static function ppHtml(
        array $vars,
        ?string $tag,
        array $backtrace,
        string $colorBack,
        string $colorText,
        string $colorHigh,
        bool $margin,
        bool $border,
    ): void {
        $preStyle = [
            'background' => $colorBack,
            'color' => $colorText,
            'padding' => '1em',
            'white-space' => 'break-spaces',
            'border' => $border ? ('dashed 1px ' . $colorHigh) : 'none',
            'margin-top' => $margin ? '1em' : '0',
            'margin-bottom' => $margin ? '1em' : '0',
        ];

        $tagStyle = [
            'display' => 'inline-block',
            'background' => $colorHigh,
            'color' => $colorBack,
            'padding' => '.5em',
            'font-size' => '1.1em',
        ];

        $hrStyle = [
            'border' => 'none',
            'border-top' => 'dashed 1px ' . $colorText,
            'margin' => '2em 0',
        ];

        echo '<pre ' . styleFromArray($preStyle) . '>';

        if ($tag) {
            echo '<span ' . styleFromArray($tagStyle) . '>';
            echo $tag;
            echo '</span>';
            echo "\n";
            echo "\n";
        }

        if (!empty($backtrace)) {
            echo '<span style="color:' . $colorHigh . '">';
            echo implode("\n", $backtrace);
            echo '</span>';
            echo '<hr ' . styleFromArray(array_merge($hrStyle, ['border-top' => 'dashed 1px ' . $colorHigh])) . '>';
        }

        echo implode('<hr ' . styleFromArray($hrStyle) . '>', $vars);
        echo '</pre>';
    }

    private static function ppText(
        array $vars,
        ?string $tag,
        array $backtrace,
    ): void {
        $textLine = '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-';

        echo "\n";
        echo $textLine;
        echo "\n";
        echo $textLine;
        echo "\n";
        echo "\n";

        if ($tag) {
            echo ' ' . str_pad('', mb_strlen($tag) + 6, '@');
            echo "\n";
            echo ' @  ' . str_pad('', mb_strlen($tag), ' ') . '  @';
            echo "\n";
            echo ' @  ' . $tag . '  @';
            echo "\n";
            echo ' @  ' . str_pad('', mb_strlen($tag), ' ') . '  @';
            echo "\n";
            echo ' ' . str_pad('', mb_strlen($tag) + 6, '@');
            echo "\n";
            echo "\n";
        }

        if (!empty($backtrace)) {
            echo implode("\n", $backtrace);
            echo "\n";
            echo $textLine;
            echo "\n";
            echo "\n";
        }

        echo implode("\n\n" . $textLine . "\n\n", $vars);
        echo "\n";
        echo "\n";
        echo $textLine;
        echo "\n";
        echo $textLine;
        echo "\n";
    }
}
