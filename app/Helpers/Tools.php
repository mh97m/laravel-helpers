<?php

if (! function_exists('intToFloat')) {
    function intToFloat($num)
    {
        return is_numeric($num) ? number_format(floatval($num), 2, '.', '') : $num;
    }
}
