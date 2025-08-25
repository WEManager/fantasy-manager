<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LicenseQuiz extends Model
{
  protected $table = "license_quizzes";

  protected $with = ['questions'];

  public function questions(): HasMany
  {
    return $this->hasMany(LicenseQuizQuestion::class);
  }
}
