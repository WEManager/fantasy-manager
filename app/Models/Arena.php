<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\SlugOptions;

class Arena extends Model {
  use HasSlug;

  protected $guarded = [];

  public function getSlugOptions(): SlugOptions {
    return SlugOptions::create()
      ->generateSlugsFrom('name')
      ->saveSlugsTo('slug');
  }

  public function getRouteKeyName() {
    return 'slug';
  }

  public function club(): BelongsTo {
    return $this->belongsTo(Club::class);
  }
}
