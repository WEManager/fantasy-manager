<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentStanding extends Model
{
    protected $guarded = [];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
