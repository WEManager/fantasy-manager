<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\UpdateClubAction;
use App\Http\Requests\UpdateClubRequest;
use App\Models\Club;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ClubController extends Controller
{
    public function index(): Response
    {
        $response = Club::paginate(40);

        return Inertia::render('clubs/index/page', compact('response'));
    }

    public function show(Club $club): Response
    {
        $club->load([
            'homegames.group.tournament',
            'homegames.hometeam',
            'homegames.awayteam',
            'awaygames.group.tournament',
            'awaygames.hometeam',
            'awaygames.awayteam',
        ]);

        return Inertia::render('clubs/show/page', compact('club'));
    }

    public function edit(Club $club): Response
    {
        return Inertia::render('clubs/edit/page', compact('club'));
    }

    public function update(UpdateClubRequest $request, Club $club, UpdateClubAction $action): RedirectResponse
    {
        $request->validated();
        $action->handle($request, $club);

        return redirect()
            ->route('club.show', $club->slug)
            ->with('message', 'Clube atualizado com sucesso!')
            ->with('type', 'success');
    }
}
