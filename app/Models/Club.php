<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Sluggable\SlugOptions;

class Club extends Model {
    use HasSlug;

    protected $fillable = ['name', 'colors', 'locale'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName() {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function colors(): Attribute {
        return new Attribute(
            set: fn($value) => json_encode($value, JSON_UNESCAPED_SLASHES),
            get: fn($value) => json_decode($value)
        );
    }

    public function homeGames(): HasMany {
        return $this->hasMany(TournamentGame::class, 'hometeam_id', 'id');
    }

    public function awayGames(): HasMany {
        return $this->hasMany(TournamentGame::class, 'awayteam_id', 'id');
    }

    public function manager(): HasOneThrough {
        return $this->hasOneThrough(User::class, ManagerContract::class, 'club_id', 'id', 'id', 'user_id');
    }

    public function tournament(): HasOneThrough {
        return $this->hasOneThrough(Tournament::class, TournamentParticipant::class, 'club_id', 'id', 'id', 'tournament_id');
    }

    public function arena(): HasOne {
        return $this->hasOne(Arena::class);
    }

    public function players(): HasManyThrough {
        return $this
            ->hasManyThrough(Player::class, Contract::class, 'club_id', 'id', 'id', 'player_id')
            ->whereDate('from', '<', date('Y-m-d'))
            ->whereDate('until', '>', date('Y-m-d'));
    }
}
