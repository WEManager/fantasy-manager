<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lineup extends Model
{
    protected $guarded = [];

    protected $casts = [
        'club_id' => 'int',
    ];
}
