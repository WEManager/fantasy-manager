<?php

namespace App\Http\Controllers;

use App\Actions\UpdateClubAction;
use App\Http\Requests\UpdateClubRequest;
use App\Models\Club;
use Inertia\Inertia;

class ClubController extends Controller
{
  public function index()
  {
    $response = Club::paginate(40);

    return Inertia::render('clubs/index/page', compact('response'));
  }

  public function show(Club $club)
  {
    $club->load([
      'homegames.group.tournament',
      'homegames.hometeam',
      'homegames.awayteam',
      'awaygames.group.tournament',
      'awaygames.hometeam',
      'awaygames.awayteam'
    ]);

    return Inertia::render('clubs/show/page', compact('club'));
  }

  public function edit(Club $club)
  {
    return Inertia::render('clubs/edit/page', compact('club'));
  }

  public function update(UpdateClubRequest $request, Club $club, UpdateClubAction $action)
  {
    $request->validated();
    $action->handle($request, $club);

    return redirect()
      ->route('club.show', $club->slug)
      ->with('message', 'Clube atualizado com sucesso!')
      ->with('type', 'success');
  }
}
