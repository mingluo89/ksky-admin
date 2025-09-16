<?php
function safe_number($value, $decimals = 0)
{
    return is_numeric($value) ? number_format($value, $decimals) : '';
}

function safe_percent($numerator, $denominator, $decimals = 0)
{
    return (is_numeric($numerator) && is_numeric($denominator) && $denominator != 0)
        ? number_format($numerator / $denominator * 100, $decimals) . '%'
        : '';
}
