<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LicenseQuizQuestion extends Model
{
  protected $casts = ['answers' => 'array'];

  protected $hidden = ['correct_answer'];

  public function getNameAttribute()
  {
    return Str::slug($this->question);
  }
}
