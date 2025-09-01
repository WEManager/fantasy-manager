<?php

declare(strict_types=1);

namespace App\Generators;

final class Player
{
    public static function firstname(string $locale): string
    {
        $firstnames = include resource_path('firstname/' . $locale . '.php');
        shuffle($firstnames);

        return $firstnames[0];
    }

    public static function lastname(string $locale): string
    {
        $lastnames = include resource_path('lastname/' . $locale . '.php');
        shuffle($lastnames);

        return $lastnames[0];
    }

    public static function age(string $type): int
    {
        return match ($type) {
            'U19' => rand(15, 19),
            'U21' => rand(19, 21),
            default => rand(20, 35),
        };
    }
}
