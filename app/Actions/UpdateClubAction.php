<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\UpdateClubRequest;
use App\Models\Club;
use Illuminate\Support\Facades\DB;

final class UpdateClubAction
{
    public function handle(UpdateClubRequest $request, Club $club): void
    {
        DB::transaction(function () use ($request, $club) {
            $club->update([
                'name' => $request->name,
                'locale' => $request->locale,
                'colors' => [
                    $request->mainColor,
                    $request->secondColor,
                    $request->thirdColor,
                ],
            ]);
        });
    }
}
