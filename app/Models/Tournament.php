<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

final class Tournament extends Model
{
    use HasSlug;

    protected $with = ['tournamentGroups', 'qualifications'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function games(): HasManyThrough
    {
        return $this->hasManyThrough(
            TournamentGame::class,
            TournamentGroup::class,
            'tournament_id', // FK em tournament_groups
            'group_id',      // FK em tournament_games
            'id',            // PK em tournaments
            'id'             // PK em tournament_groups
        );
    }

    public function getStartDateAttribute()
    {
        $groupIds = $this->tournamentGroups()->pluck('id');
        try {
            $firstGameDate = TournamentGame::whereIn('group_id', $groupIds)->orderBy('start_time', 'asc')->firstOrFail('start_time');
        } catch (Exception $exception) {
            $firstGameDate = new TournamentGame();

            $firstGameDate->start_time = now()->addCenturies(2);
        }

        return Carbon::createFromTimeString($firstGameDate->start_time)->format('Y-m-d');
    }

    public function getEndDateAttribute()
    {
        $groupIds = $this->tournamentGroups()->pluck('id');
        try {
            $lastGameDate = TournamentGame::whereIn('group_id', $groupIds)->orderBy('start_time', 'desc')->firstOrFail('start_time');
        } catch (Exception $exception) {
            $lastGameDate = new TournamentGame();
            $lastGameDate->start_time = now()->addCenturies(2);
        }

        return Carbon::createFromTimeString($lastGameDate->start_time)->addMinutes(106)->format('Y-m-d');
    }

    public function getStatusAttribute()
    {
        return match (true) {
            $this->start_date > now()->addCenturies(1) => 'NOT_DECIDED',
            $this->start_date > now() => 'NOT_STARTED',
            $this->end_date < now() => 'ENDED',
            default => 'ACTIVE',
        };
    }

    // TODO: remove groups column from tournament table to rename it to groups
    public function tournamentGroups(): HasMany
    {
        return $this->hasMany(TournamentGroup::class);
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(TournamentQualification::class);
    }

    public function seasons(): BelongsToMany
    {
        return $this->belongsToMany(Season::class, 'tournament_season');
    }

    public function standings(): HasManyThrough
    {
        return $this->hasManyThrough(TournamentStanding::class, TournamentGroup::class, 'tournament_id', 'group_id');
    }

    public function clubsParticipants(): BelongsToMany
    {
        return $this->belongsToMany(Club::class, 'tournament_participants');
    }
}
