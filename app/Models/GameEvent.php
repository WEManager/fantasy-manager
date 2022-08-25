<?php

namespace App\Models;

use App\Engines\MatchEngine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameEvent extends Model
{
    protected $guarded = [];

    protected $with = ['game'];

    /**
     * Scopes
     */

    public function scopeCurrent(Builder $builder) {
        $builder->where('event_time', '<=', now());
    }

    /**
     * Relationships
     */

    public function game(): BelongsTo {
        return $this->belongsTo(TournamentGame::class, 'game_id', 'id');
    }


    /**
     * Play this event in game
     */
    public function play(): void {
        new MatchEngine($this->game);
    }
}
