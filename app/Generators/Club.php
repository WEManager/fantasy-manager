<?php

namespace App\Generators;

class Club
{
    public static function name($locale)
    {
        $prefixes = include resource_path('club_prefix/' . $locale . '.php');
        $suffixes = include resource_path('club_suffix/' . $locale . '.php');
        shuffle($prefixes);
        shuffle($suffixes);

        $count = count($prefixes) + count($suffixes);
        $preOrSuffixRandomizer = rand(1, $count);

        $names = include resource_path('city/' . $locale . '.php');
        shuffle($names);

        // Give the club a prefix
        if ($preOrSuffixRandomizer <= count($prefixes)) {
            $clubName = $prefixes[0] . ' ' . $names[0];
        } // Give the club a suffix
        else {
            $clubName = $names[0] . ' ' . $suffixes[0];
        }

        if (\App\Club::where('name', $clubName)->first()) {
            return Club::name($locale);
        }

        return $clubName;
    }

    public static function colors()
    {
        $colors = include resource_path('club_colors/index.php');
        shuffle($colors);

        return json_encode($colors[0]);
    }
}
