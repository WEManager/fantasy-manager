<?php

namespace App\Http\Controllers;

use App\Models\LicenseQuiz;
use App\Models\User;
use Inertia\Inertia;

class LicenseQuizController extends Controller
{
  public function show(LicenseQuiz $licenseQuiz)
  {
    return Inertia::render('license-test/page', ['quiz' => $licenseQuiz]);
  }

  public function submission(LicenseQuiz $licenseQuiz)
  {
    $answers = \request()->all();
    unset($answers['_token']);

    $verfiedManager = true;

    $errors = [];

    foreach ($licenseQuiz->questions as $question) {
      $answerKey = (string) $question->id;
      if (!isset($answers[$answerKey]) || $answers[$answerKey] !== $question->correct_answer) {
        $verfiedManager = false;
        $errors[$answerKey] = true;
      }
    }

    if ($verfiedManager) {
      $user = User::find(auth()->id());
      $user->level = 1; // Give the manager a new level
      $user->save();

      if ($club = session()->get('apply_for_job_club')) {
        return redirect(link_route('club.apply', ['club' => $club]))->with('message', __('You passed the exam!'));
      }

      return redirect(link_route('home'));
    }

    return redirect()->back()->withInput()->withErrors($errors);
  }
}
