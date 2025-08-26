<?php

namespace App\Actions;

use App\Http\Requests\UpdateClubRequest;
use App\Models\Club;
use Illuminate\Support\Facades\DB;

class UpdateClubAction
{
  public function handle(UpdateClubRequest $request, Club $club)
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
