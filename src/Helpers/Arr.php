<?php

namespace App\Helpers;

class Arr
{
    public static function rand(array $array, int $count = 1): array
    {
        if ($count === 1) {
            return $array[array_rand($array, $count)];
        }

        $arrRand = [];
        foreach (array_rand($array, $count) as $item) {
            $arrRand[] = $array[$item];
        }

        return $arrRand;
    }

    public static function randOne(array $array)
    {
        return $array[array_rand($array, 1)];
    }
}
