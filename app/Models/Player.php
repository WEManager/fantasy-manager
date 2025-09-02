<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class Player extends Model
{
    use Traits\PlayerOveralls;

    protected $fillable = [
        'id',
        'full_name',
        'know_as',
        'fifa_player_id',
        'weak_foot',
        'skill_moves',
        'preferred_foot',
        'length',
        'weight',
        'nation_id',
        'birth_date',
        'preferred_position',
        'positions',
        'specialities',
        'play_styles',
        'stats',
    ];

    protected $casts = [
        'stats' => Casts\PlayerStatsCast::class,
        'positions' => 'array',
        'specialities' => 'array',
        'play_styles' => 'array',
        'birth_date' => 'datetime',
    ];

    protected $with = ['nation'];

    /** @return BelongsTo<Nation, $this> */
    public function nation(): BelongsTo
    {
        return $this->belongsTo(Nation::class);
    }

    /** @return HasOne<Contract, $this> */
    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    /** @return HasOneThrough<Club, Contract, $this> */
    public function club(): HasOneThrough
    {
        return $this->hasOneThrough(
            Club::class,
            Contract::class,
            'player_id', // FK em contracts
            'id',        // FK em clubs
            'id',        // PK em players
            'club_id'    // PK em contracts
        );
    }
}
