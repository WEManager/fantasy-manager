<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentGroup extends Model
{
    protected $fillable = [
        'name',
        'tournament_id',
    ];

    protected $with = ['games', 'upcomingGames'];

    protected $hidden = ['tournament_id', 'created_at', 'updated_at'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function games()
    {
        return $this->hasMany(TournamentGame::class, 'group_id')->orderBy('start_time');
    }

    public function upcomingGames()
    {
        return $this->hasMany(TournamentGame::class, 'group_id')->whereDate('start_time', '>=', ago('12 hours', 'date'))->whereDate('start_time', '<', date('Y-m-d', strtotime('+1 day')))->orderBy('start_time');
    }
}
