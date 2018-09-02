<?php

namespace app\modules\admin\helpers;

class ColorHelper
{
    public static $colors = [
        'red',
        'yellow',
        'aqua',
        'blue',
        'light',
        'blue',
        'green',
        'navy',
        'teal',
        'olive',
        'lime',
        'orange',
        'fuchsia',
        'purple',
        'maroon',
        'black'
    ];

    public static function getRandomColor($key = null)
    {
        if ($key === null) {
            $randKey = rand(0, count(self::$colors) - 1);
        } else {
            $randKey = $key;
        }

        return self::$colors[$randKey];
    }

    public static function getRandomColorClass($key = null)
    {
        return 'bg-' . self::getRandomColor($key);
    }
}