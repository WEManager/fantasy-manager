<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentStanding extends Model {
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

    public function getGoalDifferenceAttribute() {
        return (int) $this->scored - $this->conceded;
    }

    public function club(): BelongsTo {
        return $this->belongsTo(Club::class);
    }
}
