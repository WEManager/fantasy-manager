<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LicenseQuizQuestion extends Model
{
    protected $casts = ['answers' => 'array'];

    public function getNameAttribute()
    {
        return Str::slug($this->question);
    }
}
