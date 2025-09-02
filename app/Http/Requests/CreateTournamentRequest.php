<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\TournamentType;
use Illuminate\Foundation\Http\FormRequest;

final class CreateTournamentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, string> */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:'.implode(',', array_column(TournamentType::cases(), 'value')),
            'groups' => 'required|integer|min:1|max:8',
            'playoffs' => 'required|integer|min:0|max:16',
            'proceeding_to_playoffs' => 'required|integer|min:1|max:8',
            'selectedClubs' => 'required|array|min:1',
            'selectedClubs.*' => 'exists:clubs,id',
        ];
    }
}
