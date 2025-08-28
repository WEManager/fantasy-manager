<?php

declare(strict_types=1);

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', Controllers\HomeController::class)->name('home');

Route::resource('torneios', Controllers\TournamentController::class)
    ->only(['index', 'show', 'create', 'store'])
    ->parameters(['torneios' => 'tournament'])
    ->middlewareFor('create', 'admin')
    ->middlewareFor('store', 'admin')
    ->names('tournament');

Route::resource('clubes', Controllers\ClubController::class)
    ->only(['index', 'show', 'edit', 'update'])
    ->parameters(['clubes' => 'club'])
    ->middlewareFor('update', 'admin')
    ->middlewareFor('edit', 'admin')
    ->names('club');

Route::get('clubes/{club}/elenco', Controllers\SquadController::class)
    ->name('club.squad');

Route::get('clubes/{club}/aplicar', [Controllers\ManagerContractController::class, 'create'])
    ->middleware('auth')
    ->name('club.apply');
Route::post('clubes/aplicar', [Controllers\ManagerContractController::class, 'store'])
    ->middleware('auth')
    ->name('club.apply.store');
Route::get('clubes/{club}/renunciar', [Controllers\ManagerContractController::class, 'resign'])
    ->middleware('auth')
    ->name('club.resign');
Route::get('clubes/{club}/{squad}/formacao', [Controllers\LineupController::class, 'edit'])->name('edit_lineup');
Route::post('clubes/formacao/{lineup}', [Controllers\LineupController::class, 'update'])->name('update_lineup');

Route::get('/license-test/{licenseQuiz}', [Controllers\LicenseQuizController::class, 'show'])
    ->middleware('level0')
    ->name('license_test');
Route::post('/license-test/{licenseQuiz}/submit', [Controllers\LicenseQuizController::class, 'submission'])
    ->middleware('level0')
    ->name('license_test_validation');

Route::resource('jogadores', Controllers\PlayerController::class)
    ->only(['index', 'show'])
    ->parameters(['jogadores' => 'player'])
    ->names('player');

Route::get('partidas/{game}', Controllers\TournamentGameController::class)
    ->name('game.show');

// Route::get('/test-heading', function () {
//     $player = \App\Models\User::find(1);
//     $duelant = \App\Models\User::find(2);

//     echo \App\Engines\GameDuelsEngine::headingDuelBetween($player, $duelant, true);
// });

// Route::get('/test-game/{id}', function ($id) {
//     $game = \App\Models\TournamentGame::find($id);

//     new \App\Engines\MatchEngine($game);
// });
