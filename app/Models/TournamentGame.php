<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class TournamentGame extends Model
{
    protected $casts = [
        'hometeam_score' => 'int',
        'awayteam_score' => 'int',
        'start_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = ['group_id', 'hometeam_id', 'awayteam_id', 'created_at', 'updated_at'];

    protected $appends = ['messages', 'gameStatus', 'currentMinute'];

    /** @return HasOne<Lineup, $this> */
    public function homeLineup(): HasOne
    {
        return $this->hasOne(Lineup::class, 'club_id', 'hometeam_id');
    }

    /** @return HasOne<Lineup, $this> */
    public function awayLineup(): HasOne
    {
        return $this->hasOne(Lineup::class, 'club_id', 'awayteam_id');
    }

    public function getCurrentMinuteAttribute(): int
    {
        $minutes = now()->diffInMinutes($this->start_time);

        if ($minutes > 60) {
            $minutes = $minutes - 15;
        }

        return (int) $minutes;
    }

    public function getIsAboutToStartAttribute(): bool
    {
        return $this->status === '0' && now()->greaterThanOrEqualTo($this->start_time);
    }

    public function getIsTimeForHalftimeAttribute(): bool
    {
        return $this->status === '1'
            && $this->start_time->between(
                now()->addMinutes(45),
                now()->addMinutes(60)
            );
    }

    public function getIsTimeForSecondHalfAttribute(): bool
    {
        return $this->status === '3'
            && $this->start_time->lessThanOrEqualTo(now()->subMinutes(60));
    }

    public function getIsAboutToEndAttribute(): bool
    {
        return $this->status === '1'
            && $this->start_time->lessThanOrEqualTo(now()->subMinutes(60))
            && $this->gameEvents->count() === 0;
    }

    /** @return array{minute: int, message: string}[] */
    public function getMessagesAttribute(): array
    {
        /** @var array{minute: int, message: string}[] */
        $messages = [];

        // Ensure gameHappenings are loaded
        if (! $this->relationLoaded('gameHappenings')) {
            $this->load('gameHappenings');
        }

        foreach ($this->gameHappenings as $happening) {
            $messages[] = [
                'minute' => $happening->minute,
                'message' => str_replace(
                    ['hometeam', 'awayteam', ':SCORES:'],
                    [$this->hometeam->name, $this->awayteam->name, __('GOAL')],
                    $happening->event_description_string
                ),
            ];
        }

        return $messages;
    }

    public function getGameStatusAttribute(): string
    {
        switch ($this->status) {
            case '0':
                if ($this->start_time->isToday()) {
                    return ucfirst($this->start_time->diffForHumans());
                }
                if (! $this->start_time->isCurrentYear()) {
                    return $this->start_time->format('j/n H:i Y');
                }

                return $this->start_time->format('j/n H:i');

            case '1':
                $minutes = now()->diffInMinutes($this->start_time);

                $returnString = $minutes.'\'';

                if ($minutes > 45 && $minutes < 60) {
                    $returnString = __('Halftime');
                }
                if ($minutes >= 60) {
                    $returnString = ($minutes - 15).'\'';
                }

                return '<i class="material-icons">timelapse</i> '.$returnString;
            case '2':
                return __('Ended');
            case '3':
                return __('Waiting for second half');
            case '4':
                return __('Waiting for extra time');
            case '5':
                return __('Waiting for penalties');
            case '6':
                return __('Cancelled');
            case '7':
                return __('Postponed');
            case '8':
                return __('Not decided');
            default:
                return __('Unknown');
        }
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeAboutToStart(Builder $query): Builder
    {
        return $query->where('status', '0')
            ->where('start_time', '<=', now());
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeTimeForHalftime(Builder $query): Builder
    {
        return $query->where('status', '1')
            ->where('start_time', '<=', ago('45 minutes'))
            ->where('start_time', '>=', ago('60 minutes'));
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeAboutToEnd(Builder $query): Builder
    {
        return $query->where('status', '=', '1')
            ->where('start_time', '<=', ago('106 minutes'));
    }

    /** @return BelongsTo<TournamentGroup, $this> */
    public function group(): BelongsTo
    {
        return $this->belongsTo(TournamentGroup::class, 'group_id');
    }

    /** @return BelongsTo<Club, $this> */
    public function hometeam(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'hometeam_id');
    }

    /** @return BelongsTo<Club, $this> */
    public function awayteam(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'awayteam_id');
    }

    /** @return HasMany<GameEvent, $this> */
    public function gameEvents(): HasMany
    {
        return $this->hasMany(GameEvent::class, 'game_id');
    }

    /** @return HasMany<TournamentGameEvent, $this> */
    public function gameHappenings(): HasMany
    {
        return $this->hasMany(TournamentGameEvent::class)->orderBy('minute');
    }

    /** @return HasMany<GameEvent, $this> */
    public function hometeamEvents(): HasMany
    {
        return $this->hasMany(GameEvent::class, 'game_id')->where('club_id', $this->hometeam_id);
    }

    /** @return HasMany<GameEvent, $this> */
    public function awayteamEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id')->where('club_id', $this->awayteam_id);
    }
}
