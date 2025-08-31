<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Club;
use App\Models\ManagerContract;

final class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'admin', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];  

    public function getIsAdminAttribute(): bool {
        return $this->admin;
    }

    /** @return HasOneThrough<Club, ManagerContract, $this> */
    public function club(): HasOneThrough {
        return $this
            ->hasOneThrough(Club::class, ManagerContract::class, 'user_id', 'id', 'id', 'club_id')
            ->where('manager_contracts.status', 'ongoing');
    }
}
