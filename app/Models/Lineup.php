<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lineup extends Model
{
    protected $guarded = [];

    protected $casts = [
        'club_id' => 'int',
    ];

    protected $fillable = [
        'club_id',
        'team',
        'position_1', 'player_1',
        'position_2', 'player_2',
        'position_3', 'player_3',
        'position_4', 'player_4',
        'position_5', 'player_5',
        'position_6', 'player_6',
        'position_7', 'player_7',
        'position_8', 'player_8',
        'position_9', 'player_9',
        'position_10', 'player_10',
        'position_11', 'player_11',
        'substitute_1', 'substitute_2', 'substitute_3',
        'substitute_4', 'substitute_5', 'substitute_6',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
