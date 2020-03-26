<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Colors implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $colors = json_decode($value, true);
        if (!isset($colors[2])) {
            $colors[2] = ($colors[1] == '#FFFFFF') ? '#000000' : '#FFFFFF';
        }

        return $colors;
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return json_encode($value);
    }
}
