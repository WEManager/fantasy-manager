<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lineup extends Model
{
    protected $fillable = [
        'team',
        'club_id',
    ];
}
