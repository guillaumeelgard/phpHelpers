<?php

if (!function_exists('is_ajax')) {
    function is_ajax(): bool
    {
        return 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
    }
}
