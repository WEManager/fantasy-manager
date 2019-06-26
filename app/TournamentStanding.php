<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentStanding extends Model
{
    protected $guarded = [];

    protected $casts = ['group_id' => 'int'];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
