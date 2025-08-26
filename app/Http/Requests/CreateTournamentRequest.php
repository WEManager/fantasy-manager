<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTournamentRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:league,cup,championship',
      'groups' => 'required|integer|min:1|max:8',
      'playoffs' => 'required|integer|min:0|max:16',
      'proceeding_to_playoffs' => 'required|integer|min:1|max:8',
      'selectedClubs' => 'required|array|min:1',
      'selectedClubs.*' => 'exists:clubs,id',
    ];
  }
}
