<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class TournamentGroup extends Model
{
    protected $fillable = ['name', 'tournament_id'];

    protected $hidden = ['tournament_id', 'created_at', 'updated_at'];

    /** @return BelongsTo<Tournament, $this> */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /** @return HasMany<Fixture, $this> */
    public function games(): HasMany
    {
        return $this->hasMany(Fixture::class, 'group_id')->orderBy('start_time');
    }

    /** @return HasMany<Fixture, $this> */
    public function upcomingGames(): HasMany
    {
        return $this->hasMany(Fixture::class, 'group_id')
            ->whereDate('start_time', '>=', now()->subHours(12))
            ->whereDate('start_time', '<', now()->addDay())
            ->orderBy('start_time');
    }

    /** @return HasMany<TournamentStanding, $this> */
    public function standings(): HasMany
    {
        return $this->hasMany(TournamentStanding::class, 'group_id')
            ->orderBy('points', 'desc')
            ->orderByRaw('(scored - conceded) desc');
    }
}
