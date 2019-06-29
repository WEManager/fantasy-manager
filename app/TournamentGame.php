<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TournamentGame extends Model
{
    protected $guarded = [];

    protected $casts = [
        'hometeam_score' => 'int',
        'awayteam_score' => 'int',
    ];

    protected $hidden = ['group_id', 'hometeam_id', 'awayteam_id', 'created_at', 'updated_at'];

    public function getIsAboutToStartAttribute()
    {
        return $this->status == '0' && $this->start_time <= now();
    }

    public function getIsTimeForHalftimeAttribute()
    {
        return $this->status == '1' && $this->start_time <= ago('45 minutes') && $this->start_time <= ago('60 minutes');
    }

    public function getIsTimeForSecondHalfAttribute()
    {
        return $this->status == '3' && $this->start_time <= ago('60 minutes');
    }

    public function getIsAboutToEndAttribute()
    {
        return $this->status == '1' && $this->start_time <= ago('106 minutes');
    }

    public function getGameStatusAttribute()
    {
        switch ($this->status) {
            case '0':
                return __('Not started');
            case '1':
                return __('Ongoing');
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
     * Scopes
     */

    public function scopeAboutToStart(Builder $builder)
    {
        return $builder->where('status', '0')
            ->where('start_time', '<=', now());
    }

    public function scopeTimeForHalftime(Builder $builder)
    {
        return $builder->where('status', '1')
            ->where('start_time', '<=', ago('45 minutes'))
            ->where('start_time', '>=', ago('60 minutes'));
    }

    public function scopeAboutToEnd(Builder $builder) {
        return $builder->where('status', '=', '1')
            ->where('start_time', '<=', ago('106 minutes'));
    }

    /**
     * Relationships
     */

    public function group()
    {
        return $this->belongsTo(TournamentGroup::class, 'group_id');
    }

    public function hometeam()
    {
        return $this->belongsTo(Club::class, 'hometeam_id');
    }

    public function awayteam()
    {
        return $this->belongsTo(Club::class, 'awayteam_id');
    }

    public function gameEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id');
    }

    public function hometeamEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id')->where('club_id', $this->hometeam_id);
    }

    public function awayteamEvents()
    {
        return $this->hasMany(GameEvent::class, 'game_id')->where('club_id', $this->awayteam_id);
    }
}
