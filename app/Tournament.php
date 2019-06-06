<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'type',
        'groups',
        'playoffs',
        'participants',
        'proceeding_to_playoffs',
    ];

    public function tournamentGroups()
    {
        return $this->hasMany(TournamentGroup::class);
    }
}
