<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerContract extends Model
{
    protected $fillable = [
        'from',
        'wage',
        'until',
        'club_id',
        'user_id',
    ];
}
