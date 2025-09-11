<?php

declare(strict_types=1);

namespace App\Models;

use App\Engines\MatchEngine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class GameEvent extends Model
{
    protected $casts = [
        'event_time' => 'datetime',
    ];

    protected $with = ['fixture'];

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('event_time', '<=', now());
    }

    /** @return BelongsTo<Fixture, $this> */
    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }

    public function play(): void
    {
        new MatchEngine($this->fixture);
    }
}
