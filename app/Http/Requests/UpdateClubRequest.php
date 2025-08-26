<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClubRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'name' => 'required|string|max:255',
      'locale' => 'required|string|max:3',
      'mainColor' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
      'secondColor' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
      'thirdColor' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
    ];
  }
}
