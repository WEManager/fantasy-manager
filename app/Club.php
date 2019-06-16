<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = ['name', 'locale'];

    public function players()
    {
        return $this->belongsToMany(Person::class, 'player_contracts', 'club_id', 'person_id')
            // Only get contracts that are currently valid
            ->whereDate('from', '<', date('Y-m-d'))
            ->whereDate('until', '>', date('Y-m-d'));
    }
}
