<?php

use Illuminate\Support\Str;

if (!function_exists('limitText')) {
    /**
     * Limits a string to a certain number of characters, handling multi-byte characters correctly.
     *
     * @param string|null $value
     * @param int $limit
     * @param string $end
     * @return string
     */
    function limitText($value, $limit = 100, $end = '...')
    {
        if (empty($value)) {
            return '';
        }
        
        return Str::limit($value, $limit, $end);
    }
}
