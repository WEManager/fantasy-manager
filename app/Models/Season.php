<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Season extends Model {
    protected $guarded = [];

    public function tournaments(): BelongsToMany {
        return $this->belongsToMany(Tournament::class, 'tournament_season');
    }

    public function games(): HasManyThrough {
        return $this->hasManyThrough(TournamentGame::class, Tournament::class);
    }
}
