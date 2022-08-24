<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentSeason extends Model
{
    protected $guarded = [];

    protected $table = 'tournament_season';

    public $timestamps = false;

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
