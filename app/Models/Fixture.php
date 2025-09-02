<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FixtureStatus;
use App\Enums\FixtureType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Fixture extends Model
{
    protected $casts = [
        'type' => FixtureType::class,
        'status' => FixtureStatus::class,
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
        return $this->status === FixtureStatus::NOT_STARTED
            && now()->greaterThanOrEqualTo($this->start_time);
    }

    public function getIsTimeForHalftimeAttribute(): bool
    {
        return $this->status === FixtureStatus::ACTIVE
            && $this->start_time->between(
                now()->addMinutes(45),
                now()->addMinutes(60)
            );
    }

    public function getIsTimeForSecondHalfAttribute(): bool
    {
        return $this->status === FixtureStatus::WAITING_FOR_SECOND_HALF
            && $this->start_time->lessThanOrEqualTo(now()->subMinutes(60));
    }

    public function getIsAboutToEndAttribute(): bool
    {
        return $this->status === FixtureStatus::ACTIVE
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
        return match ($this->status) {
            FixtureStatus::NOT_STARTED => $this->getNotStartedStatusText(),
            FixtureStatus::ACTIVE => $this->getActiveStatusText(),
            FixtureStatus::ENDED => __('Finalizado'),
            FixtureStatus::WAITING_FOR_SECOND_HALF => __('Aguardando segundo tempo'),
            FixtureStatus::WAITING_FOR_EXTRA_TIME => __('Aguardando prorrogação'),
            FixtureStatus::WAITING_FOR_PENALTIES => __('Aguardando pênaltis'),
            FixtureStatus::CANCELLED => __('Cancelado'),
            FixtureStatus::POSTPONED => __('Adiado'),
            FixtureStatus::NOT_DECIDED => __('Não decidido'),
        };
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeAboutToStart(Builder $query): Builder
    {
        return $query->where('status', FixtureStatus::NOT_STARTED)
            ->where('start_time', '<=', now());
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeTimeForHalftime(Builder $query): Builder
    {
        return $query->where('status', FixtureStatus::ACTIVE)
            ->where('start_time', '<=', now()->subMinutes(45))
            ->where('start_time', '>=', now()->subMinutes(60));
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeAboutToEnd(Builder $query): Builder
    {
        return $query->where('status', FixtureStatus::ACTIVE)
            ->where('start_time', '<=', now()->subMinutes(106));
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
        return $this->hasMany(GameEvent::class);
    }

    /** @return HasMany<TournamentGameEvent, $this> */
    public function gameHappenings(): HasMany
    {
        return $this->hasMany(TournamentGameEvent::class, 'fixture_id')->orderBy('minute');
    }

    /** @return HasMany<GameEvent, $this> */
    public function hometeamEvents(): HasMany
    {
        return $this->hasMany(GameEvent::class)
            ->where('club_id', $this->hometeam_id);
    }

    /** @return HasMany<GameEvent, $this> */
    public function awayteamEvents()
    {
        return $this->hasMany(GameEvent::class)
            ->where('club_id', $this->awayteam_id);
    }

    private function getNotStartedStatusText(): string
    {
        if ($this->start_time->isToday()) {
            return ucfirst($this->start_time->diffForHumans());
        }
        if (! $this->start_time->isCurrentYear()) {
            return $this->start_time->format('j/n H:i Y');
        }

        return $this->start_time->format('j/n H:i');
    }

    private function getActiveStatusText(): string
    {
        $minutes = now()->diffInMinutes($this->start_time);

        $returnString = $minutes.'\'';

        if ($minutes > 45 && $minutes < 60) {
            $returnString = __('Intervalo');
        }
        if ($minutes >= 60) {
            $returnString = ($minutes - 15).'\'';
        }

        return '<i class="material-icons">timelapse</i> '.$returnString;
    }
}
