<?php

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst(string $str, string $encoding = 'UTF-8'): string
    {
        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) . mb_substr($str, 1, null, $encoding);
    }
}

if (!function_exists('mb_ucwords')) {
    function mb_ucwords(string $str, string $encoding = 'UTF-8'): string
    {
        return preg_replace_callback(
            '/[\wÀ-ÿ]+/',
            function ($matches) use ($encoding) {
                return mb_ucfirst($matches[0], $encoding);
            },
            $str
        );
    }
}

if (!function_exists('formatFirstName')) {
    function formatFirstName(string $str, string $encoding = 'UTF-8'): string
    {
        return mb_ucwords(mb_strtolower($str, $encoding));
    }
}
