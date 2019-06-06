<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentGame extends Model
{
    protected $guarded = [];

    protected $with = ['playerOne', 'playerTwo'];

    protected $hidden = ['group_id', 'first_player_id', 'second_player_id', 'created_at', 'updated_at'];

    public function group()
    {
        return $this->belongsTo(TournamentGroup::class, 'group_id');
    }

    public function playerOne()
    {
        return $this->belongsTo(User::class, 'first_player_id');
    }

    public function playerTwo()
    {
        return $this->belongsTo(User::class, 'second_player_id');
    }

    public function gameEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id');
    }

    public function playerOneEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id')->where('player_id', $this->first_player_id);
    }

    public function playerTwoEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id')->where('player_id', $this->second_player_id);
    }
}
