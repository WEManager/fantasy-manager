<?php

namespace App\Models;

use App\Traits\PlayerOveralls;
use App\Traits\PlayerTraitStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Player extends Model {
  use PlayerTraitStats;
  use PlayerOveralls;

  protected $guarded = [];

  protected $with = ['nation'];

  public function nation(): HasOne {
    return $this->hasOne(Nation::class, 'fifa_id', 'nation_fifa_id');
  }

  public function club(): HasOneThrough {
    return $this->hasOneThrough(Club::class, Contract::class, 'player_id', 'id', 'id', 'club_id')
      ->whereDate('from', '<', date('Y-m-d'))
      ->whereDate('until', '>', date('Y-m-d'));
  }

  public function contract() {
    return $this->hasOne(Contract::class, 'player_id')
      ->whereDate('from', '<', date('Y-m-d'))
      ->whereDate('until', '>', date('Y-m-d'));
  }
}
