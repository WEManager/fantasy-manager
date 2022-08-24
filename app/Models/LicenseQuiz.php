<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseQuiz extends Model
{
    protected $table = "license_quizzes";

    public function questions()
    {
        return $this->hasMany(LicenseQuizQuestion::class);
    }
}
