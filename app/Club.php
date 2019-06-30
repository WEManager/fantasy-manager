<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;

class Club extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'colors', 'locale'];

    protected $with = ['tournament'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function getClubColorsAttribute()
    {
        $colors = json_decode($this->colors);

        if (!isset($colors[2])) {
            $colors[2] = ($colors[1] == '#FFFFFF') ? '#000000' : '#FFFFFF';
        }

        return $colors;
    }

    public function manager()
    {
        return $this->hasOneThrough(User::class, ManagerContract::class, 'club_id', 'id', 'id', 'user_id');
    }

    public function tournament()
    {
        $season = Season::whereDate('start_time', '<=', date('Y-m-d'))
            ->whereDate('end_time', '>=', date('Y-m-d'))
            ->first();

        return $this->hasOneThrough(Tournament::class, TournamentParticipant::class, 'club_id', 'id', 'id', 'tournament_id')
            ->where('season', $season->id);
    }

    public function players($type = [])
    {
        $players = $this->belongsToMany(Person::class, 'player_contracts', 'club_id', 'person_id')
            // Only get contracts that are currently valid
            ->whereDate('from', '<', date('Y-m-d'))
            ->whereDate('until', '>', date('Y-m-d'));

        if (count($type) > 0) {
            $players->whereIn('type', $type);
        }

        return $players;
    }
}
