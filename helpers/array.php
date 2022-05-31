<?php

namespace Bokad\Helpers\Array;

function array_map_assoc(callable $callback, array $array): array
{
    $return = [];
    foreach ($array as $k => $v) {
        $return[] = $callback($k, $v);
    }
    return $return;
}

function styleFromArray(array $style): string
{
    return 'style="' . implode(';', array_map_assoc(fn ($k, $v) => "$k:$v", $style)) . '"';
}
