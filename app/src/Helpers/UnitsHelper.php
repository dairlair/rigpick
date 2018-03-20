<?php declare(strict_types = 1);

namespace App\Helpers;


class UnitsHelper
{
    public const UNITS = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    public static function formatBytes(int $size, int $base = 1024)
    {
        for ($i = 0; $size > $base; $i++) {
            $size /= $base;
        }

        $endIndex = strpos((string)$size, ".") + 3;

        return substr((string)$size, 0, $endIndex). ' '. self::UNITS[$i];
    }
}
