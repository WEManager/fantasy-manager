<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nation extends Model
{
  protected $guarded = [];

  public function players(): HasMany
  {
    return $this->hasMany(Player::class, 'nation_fifa_id', 'fifa_id');
  }
}
