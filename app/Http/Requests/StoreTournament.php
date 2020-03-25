<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTournament extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'team' => 'required',
            'groups' => 'integer',
            'playOffs' => 'required|boolean',
            'competitionType' => 'required',
            'selectedClubs' => 'required',
            'recurringEveryOfYear' => 'required|integer',
            'proceedingToPlayoffs' => 'integer',
        ];
    }
}
