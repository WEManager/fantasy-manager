<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentGame extends Model
{
    protected $guarded = [];

    protected $with = ['hometeam', 'awayteam'];

    protected $hidden = ['group_id', 'hometeam_id', 'awayteam_id', 'created_at', 'updated_at'];

    public function group()
    {
        return $this->belongsTo(TournamentGroup::class, 'group_id');
    }

    public function hometeam()
    {
        return $this->belongsTo(Club::class, 'hometeam_id');
    }

    public function awayteam()
    {
        return $this->belongsTo(Club::class, 'awayteam_id');
    }

    public function gameEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id');
    }

    public function hometeamEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id')->where('club_id', $this->hometeam_id);
    }

    public function awayteamEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id')->where('club_id', $this->awayteam_id);
    }
}
