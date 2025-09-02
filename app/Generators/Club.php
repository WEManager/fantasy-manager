<?php

declare(strict_types=1);

namespace App\Generators;

final class Club
{
    public string $town = '';

    /** @return array<int, string> */
    public static function colors(): array
    {
        $colors = include resource_path('club_colors/index.php');

        return $colors[array_rand($colors)];
    }

    public function name(string $locale): string
    {
        $prefixes = include resource_path('club_prefix/'.$locale.'.php');
        shuffle($prefixes);

        $suffixes = include resource_path('club_suffix/'.$locale.'.php');
        shuffle($suffixes);

        $count = count($prefixes) + count($suffixes);

        $preOrSuffixRandomizer = rand(1, $count);

        $names = include resource_path('city/'.$locale.'.php');
        shuffle($names);

        $this->town = $names[0];

        // Give the club a prefix
        if ($preOrSuffixRandomizer <= count($prefixes)) {
            $clubName = $prefixes[0].' '.$names[0];
        } // Give the club a suffix
        else {
            $clubName = $names[0].' '.$suffixes[0];
        }

        if (\App\Models\Club::query()->where('name', $clubName)->first()) {
            return $this->name($locale);
        }

        return $clubName;
    }
}
