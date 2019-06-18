<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $guarded = [];

    public function tournamentGroups()
    {
        return $this->hasMany(TournamentGroup::class);
    }
}
