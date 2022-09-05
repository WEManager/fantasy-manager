<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentGroup extends Model {
  protected $guarded = [];

  // protected $with = ['upcomingGames', 'standings'];

  protected $hidden = ['created_at', 'updated_at'];

  public function tournament():BelongsTo {
    return $this->belongsTo(Tournament::class);
  }

  public function games(): HasMany {
    return $this->hasMany(TournamentGame::class, 'group_id');
  }

  public function upcomingGames(): HasMany {
    return $this->hasMany(TournamentGame::class, 'group_id')
      ->whereDate('start_time', '>=', ago('12 hours', 'date'))
      ->whereDate('start_time', '<', date('Y-m-d', strtotime('+1 day')))
      ->orderBy('start_time');
  }

  public function standings(): HasMany {
    return $this->hasMany(TournamentStanding::class, 'group_id')
      ->orderBy('points', 'desc')
      ->orderByRaw('(scored - conceded) desc');
  }
}
