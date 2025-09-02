<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ManagerContract extends Model
{
    protected $fillable = [
        'from',
        'wage',
        'until',
        'club_id',
        'user_id',
    ];
}
