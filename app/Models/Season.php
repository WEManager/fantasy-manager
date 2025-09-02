<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

final class Season extends Model
{
    /** @return BelongsToMany<Tournament, $this> */
    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class, 'tournament_season');
    }

    /** @return HasManyThrough<Fixture, Tournament, $this> */
    public function games(): HasManyThrough
    {
        return $this->hasManyThrough(Fixture::class, Tournament::class);
    }
}
