<?php

namespace App;

use App\Engines\MatchEngine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class GameEvent extends Model
{
    protected $guarded = [];

    protected $with = ['game'];

    /**
     * Scopes
     */

    public function scopeCurrent(Builder $builder)
    {
        $builder->where('event_time', '<=', now());
    }

    /**
     * Relationships
     */

    public function game()
    {
        return $this->belongsTo(TournamentGame::class, 'game_id', 'id');
    }


    /**
     * Play this event in game
     */
    public function play()
    {
        new MatchEngine($this->game);
    }
}
