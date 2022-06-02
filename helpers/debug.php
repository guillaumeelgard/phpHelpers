<?php

use Bokad\Helpers\Debug;

if (!function_exists('explicit_var')) {
    function explicit_var(mixed $var): string
    {
        return Debug::explicit_var($var);
    }
}

if (!function_exists('p')) {
    function p(mixed ...$vars): void
    {
        (new \ReflectionClass('Bokad\Helpers\Debug'))->getMethod('p')->invokeArgs(null, func_get_args());
    }
}

if (!function_exists('pp')) {
    function pp(array $varsArray = [], array $options = []): void
    {
        (new \ReflectionClass('Bokad\Helpers\Debug'))->getMethod('pp')->invokeArgs(null, func_get_args());
    }
}

if (!function_exists('d')) {
    function d(mixed ...$vars): never
    {
        Debug::moreOffset();
        (new \ReflectionClass('Bokad\Helpers\Debug'))->getMethod('d')->invokeArgs(null, func_get_args());
    }
}

if (!function_exists('dd')) {
    function dd(array $varsArray = [], array $options = []): never
    {
        Debug::moreOffset();
        (new \ReflectionClass('Bokad\Helpers\Debug'))->getMethod('dd')->invokeArgs(null, func_get_args());
    }
}
