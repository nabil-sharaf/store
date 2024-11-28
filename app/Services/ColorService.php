<?php
// app/Services/ColorService.php
namespace App\Services;

class ColorService
{
    protected static $colorMap = [
        // الألوان الأساسية
        'red' => '#FF0000',
        'blue' => '#0000FF',
        'green' => '#008000',
        'yellow' => '#FFFF00',
        'black' => '#000000',
        'white' => '#FFFFFF',

        // الألوان الفرعية
        'pink' => '#FFC0CB',
        'purple' => '#800080',
        'orange' => '#FFA500',
        'brown' => '#A52A2A',
        'gray' => '#C0C0C0',
        'navy' => '#000080',

        // درجات الأزرق
        'lightblue' => '#ADD8E6',
        'skyblue' => '#87CEEB',
        'darkblue' => '#00008B',

        // درجات الأخضر
        'lime' => '#00FF00',
        'darkgreen' => '#006400',
        'olive' => '#808000',

        // درجات الأحمر
        'darkred' => '#8B0000',
        'maroon' => '#800000',
        'crimson' => '#DC143C',

        // درجات الرمادي
        'lightgray' => '#D3D3D3',
        'silver' => '#C0C0C0',
        'darkgray' => '#A9A9A9',

        'beige' => '#F5F5DC',
        'camel' => '#C19A6B',

    ];

    public static function resolveColorCode($colorValue)
    {
// التحقق إذا كان كود اللون يبدأ بـ #
        if (str_starts_with($colorValue, '#')) {
            return $colorValue;
        }

        // البحث عن اللون في القائمة
        $lowercaseColor = strtolower($colorValue);
        return self::$colorMap[$lowercaseColor] ?? '#000000';
    }
}
