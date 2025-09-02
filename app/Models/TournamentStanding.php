<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TournamentStanding extends Model
{
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

    public function getGoalDifferenceAttribute(): int
    {
        return (int) $this->scored - $this->conceded;
    }

    /** @return BelongsTo<Club, $this> */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
