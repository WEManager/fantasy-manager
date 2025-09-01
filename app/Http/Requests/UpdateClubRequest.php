<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateClubRequest extends FormRequest
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
            'mainColor' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'secondColor' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'thirdColor' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
        ];
    }
}
