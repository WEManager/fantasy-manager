<?php

declare(strict_types=1);

namespace App\Generators;

use App\Models\Arena as ModelsArena;
use Exception;

final class Arena
{
    public function name(string $locale, string $town): string
    {
        $arenaNames = include resource_path('arena/'.$locale.'.php');
        shuffle($arenaNames);

        foreach ($arenaNames as $arenaName) {
            $name = str_replace('[town]', $town, $arenaName);

            if (! ModelsArena::query()->where('name', $name)->first()) {
                return $name;
            }
        }

        throw new Exception('No available arena names for town '.$town);
    }
}
