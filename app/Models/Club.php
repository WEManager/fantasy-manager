<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ClubFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

final class Club extends Model
{
    /** @use HasFactory<ClubFactory> */
    use HasFactory;

    use HasSlug;

    protected $fillable = ['name', 'colors'];

    protected $casts = [
        'colors' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /** @return HasMany<Fixture, $this> */
    public function homeGames(): HasMany
    {
        return $this->hasMany(Fixture::class, 'hometeam_id', 'id');
    }

    /** @return HasMany<Fixture, $this> */
    public function awayGames(): HasMany
    {
        return $this->hasMany(Fixture::class, 'awayteam_id', 'id');
    }

    /** @return HasOneThrough<User, ManagerContract, $this> */
    public function manager(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, ManagerContract::class, 'club_id', 'id', 'id', 'user_id');
    }

    /** @return HasOneThrough<Tournament, TournamentParticipant, $this> */
    public function tournament(): HasOneThrough
    {
        return $this->hasOneThrough(Tournament::class, TournamentParticipant::class, 'club_id', 'id', 'id', 'tournament_id');
    }

    /** @return HasOne<Arena, $this> */
    public function arena(): HasOne
    {
        return $this->hasOne(Arena::class);
    }

    /**
     * @param  array<string>  $contractType
     * @return HasManyThrough<Player, Contract, $this>
     * */
    public function players(array $contractType = []): HasManyThrough
    {
        $query = $this
            ->hasManyThrough(Player::class, Contract::class, 'club_id', 'id', 'id', 'player_id')
            ->whereDate('from', '<', now())
            ->whereDate('until', '>', now());

        if (count($contractType) > 0) {
            $query->whereIn('contracts.type', $contractType);
        }

        return $query;
    }
}
