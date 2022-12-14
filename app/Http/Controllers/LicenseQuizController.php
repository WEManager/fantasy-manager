<?php

namespace App\Http\Controllers;

use App\Models\LicenseQuiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LicenseQuizController extends Controller
{
    public function show(LicenseQuiz $licenseQuiz)
    {
        return view('license-quizzes.show')->with(['quiz' => $licenseQuiz]);
    }

    public function submission(LicenseQuiz $licenseQuiz)
    {
        $answers = \request()->all();
        unset($answers['_token']);

        $verfiedManager = true;

        $errors = [];

        foreach ($licenseQuiz->questions as $question) {
            if (!isset($answers[$question->name]) || $answers[$question->name] !== $question->correct_answer) {
                $verfiedManager = false;
                $errors[$question->name] = true;
            }
        }

        if ($verfiedManager) {
            $user = User::find(auth()->id());
            $user->level = 1; // Give the manager a new level
            $user->save();

            if ($club = session()->get('apply_for_job_club')) {
                return redirect(link_route('apply_for_job', ['club' => $club]))->with('message', __('You passed the exam!'));
            }

            return redirect(link_route('home'));
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }
}
