<?php
/**
 * Created by PhpStorm.
 * User: jimmiejohansson
 * Date: 2019-06-16
 * Time: 15:06
 */

namespace App\Generators;


class Player
{
    public static function firstname($locale)
    {
        $firstnames = include resource_path('firstname/' . $locale . '.php');
        shuffle($firstnames);

        return $firstnames[0];
    }

    public static function lastname($locale)
    {
        $lastnames = include resource_path('lastname/' . $locale . '.php');
        shuffle($lastnames);

        return $lastnames[0];
    }

    public static function nationality($locale)
    {
        return nationalityBasedOnLocale($locale);
    }

    public static function age($type)
    {
        if ($type == 'U19') {
            return rand(15, 19);
        }
        if ($type == 'U21') {
            return rand(19, 21);
        }
        return rand(20, 35);
    }
}