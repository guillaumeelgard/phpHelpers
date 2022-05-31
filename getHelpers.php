<?php

$files = scandir(__DIR__ . '/helpers');
$files = array_filter($files, function ($file) {
    $file = __DIR__ . '/helpers/' . $file;
    if (file_exists($file) && is_file($file)) {
        require_once $file;
    }
});
