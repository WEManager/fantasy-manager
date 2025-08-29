<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\PlayerOveralls;
use App\Traits\PlayerTraitStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class Player extends Model
{
    use PlayerOveralls;
    use PlayerTraitStats;

    protected $guarded = [];

    protected $appends = ['technical', 'mental', 'physical', 'goalkeeping'];

    protected $with = ['nation'];

    /** @return HasOne<Nation, $this> */
    public function nation(): HasOne
    {
        return $this->hasOne(Nation::class);
    }

    /** @return HasOne<Contract, $this> */
    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    /** @return HasOneThrough<Club, Contract, $this> */
    public function club(): HasOneThrough
    {
        return $this->hasOneThrough(Club::class, Contract::class);
    }
}
