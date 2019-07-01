<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lineup extends Model
{
    protected $guarded = [];

    protected $casts = [
        'club_id' => 'int',
        'player_1' => 'int',
        'player_2' => 'int',
        'player_3' => 'int',
        'player_4' => 'int',
        'player_5' => 'int',
        'player_6' => 'int',
        'player_7' => 'int',
        'player_8' => 'int',
        'player_9' => 'int',
        'player_10' => 'int',
        'player_11' => 'int',
    ];
}
