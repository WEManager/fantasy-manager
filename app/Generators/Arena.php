<?php

namespace App\Generators;

class Arena {

    public function name($locale, $town)
    {
        $arenaNames = include resource_path('arena/' . $locale . '.php');
        shuffle($arenaNames);

        foreach ($arenaNames as $arenaName) {
            $name = str_replace('[town]', $town, $arenaName);

            if (!\App\Models\Arena::where('name', $name)->first()) {
                return $name;
            }
        }

        throw new \Exception('No available arena names for town ' . $town);
    }
}