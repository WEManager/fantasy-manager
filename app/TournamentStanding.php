<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentStanding extends Model
{
    protected $guarded = [];

    protected $with = ['club'];

    protected $casts = [
        'group_id' => 'int',
        'won' => 'int',
        'tie' => 'int',
        'lost' => 'int',
        'scored' => 'int',
        'conceded' => 'int',
        'points' => 'int',
    ];

    public function getGoalDifferenceAttribute()
    {
        return (int) $this->scored - $this->conceded;
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
