<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2020/01/13
 * Time: 11:44
 */

namespace vendor\project\helpers;


class Bargain
{
    private static $rule = [
        0 => 20,
        5 => 40,
        15 => 60,
        30 => 80,
        50 => 100,
        75 => 120,
        105 => 140,
        140 => 160,
        180 => 180,
        225 => 210,
        275 => 240,
        330 => 300,
        390 => 320,
        455 => 350,
        525 => 400,
        610 => 500,
    ];

    public static function go($price, $count)
    {
        $arr = [];
        for ($i = 1; $i <= $count; $i++) {
            if ($i == $count) {
                array_push($arr, round($price - array_sum($arr), 2));
                break;
            }
            if ($i == 1) {
                array_push($arr, self::randFloat($price * 0.4, $price * 0.6));
            } else {
                $max = ($price - array_sum($arr)) / $i;
                array_push($arr, self::randFloat(0, $max));
            }
        }
        var_dump($arr);
        var_dump(array_sum($arr));
        exit();
    }

    public static function randFloat($min, $max)
    {
        $num = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        return (float)sprintf("%.2f", $num);
    }
}