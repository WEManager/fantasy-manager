<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentParticipant extends Model
{
  protected $guarded = [];

  public function tournament(): BelongsTo
  {
    return $this->belongsTo(Tournament::class);
  }

  public function club(): BelongsTo
  {
    return $this->belongsTo(Club::class);
  }
}
