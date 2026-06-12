<?php

if (! function_exists('esc_str')) {
    function esc_str($value, string $context = 'html'): string
    {
        if (is_array($value) || is_object($value)) {
            return '';
        }

        return esc((string)($value ?? ''), $context);
    }
}