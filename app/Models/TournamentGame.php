<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TournamentGame extends Model
{
  protected $guarded = [];

  protected $casts = [
    'hometeam_score' => 'int',
    'awayteam_score' => 'int',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  protected $hidden = ['group_id', 'hometeam_id', 'awayteam_id', 'created_at', 'updated_at'];

  public function homeLineup(): HasOne
  {
    return $this->hasOne(Lineup::class, 'club_id', 'hometeam_id');
  }

  public function awayLineup(): HasOne
  {
    return $this->hasOne(Lineup::class, 'club_id', 'awayteam_id');
  }

  public function getCurrentMinuteAttribute()
  {
    $minutes = round((strtotime(now()) - strtotime($this->start_time)) / 60);

    if ($minutes > 60) {
      $minutes = $minutes - 15;
    }

    return $minutes;
  }

  public function getIsAboutToStartAttribute()
  {
    return $this->status === '0' && $this->start_time <= now();
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
    return $this->status == '1' && $this->start_time <= ago('106 minutes') && $this->gameEvents->count() == 0;
  }

  public function getMessagesAttribute()
  {
    $messages = [];

    foreach ($this->gameHappenings as $happening) {
      $messages[] = [
        'minute' => $happening->minute,
        'message' => str_replace(
          ['hometeam', 'awayteam', ':SCORES:'],
          [$this->hometeam->name, $this->awayteam->name, _('GOAL')],
          $happening->event_description_string
        )
      ];
    }

    return $messages;
  }

  public function getGameStatusAttribute()
  {
    switch ($this->status) {
      case '0':
        if (date('Y-m-d') == date('Y-m-d', strtotime($this->start_time))) {
          return ucfirst(Carbon::createFromTime(
            date('H', strtotime($this->start_time)),
            date('i', strtotime($this->start_time)),
            date('s', strtotime($this->start_time)),
            config('app.timezone')
          )->diffForHumans());
        } elseif (date('Y') != date('Y', strtotime($this->start_time))) {
          return date('j/n H:i Y', strtotime($this->start_time));
        } else {
          return date('j/n H:i', strtotime($this->start_time));
        }
      case '1':
        $minutes = round((strtotime(now()) - strtotime($this->start_time)) / 60);

        $returnString = $minutes . '\'';

        if ($minutes > 45 && $minutes < 60) {
          $returnString = __('Halftime');
        }
        if ($minutes >= 60) {
          $returnString = ($minutes - 15) . '\'';
        }

        return '<i class="material-icons">timelapse</i> ' . $returnString;
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

  public function scopeAboutToEnd(Builder $builder)
  {
    return $builder->where('status', '=', '1')
      ->where('start_time', '<=', ago('106 minutes'));
  }

  /**
   * Relationships
   */

  public function group(): BelongsTo
  {
    return $this->belongsTo(TournamentGroup::class, 'group_id');
  }

  public function hometeam(): BelongsTo
  {
    return $this->belongsTo(Club::class, 'hometeam_id');
  }

  public function awayteam(): BelongsTo
  {
    return $this->belongsTo(Club::class, 'awayteam_id');
  }

  public function getHometeamNameAttribute()
  {
    return Club::find($this->hometeam_id)->get('id', 'name');
  }

  public function getAwayteamNameAttribute()
  {
    return Club::find($this->hometeam_id)->get('id', 'name');
  }

  public function gameEvents(): HasMany
  {
    return $this->hasMany(GameEvent::class, 'game_id');
  }

  public function gameHappenings()
  {
    return $this->hasMany(TournamentGameEvent::class)->orderBy('minute');
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
