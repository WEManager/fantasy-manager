<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model {
  protected $fillable = [
    'from',
    'wage',
    'type',
    'until',
    'club_id',
    'player_id',
  ];

  public function player(): BelongsTo {
    return $this->belongsTo(Player::class);
  }

  public function club(): BelongsTo {
    return $this->belongsTo(Club::class);
  }
}
