<?php

use App\Http\Controllers;
use App\Http\Controllers\SeasonController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/auth.php';

Route::get('/', Controllers\HomeController::class)->name('home');

Route::resource('torneios', Controllers\TournamentController::class)
  ->only(['show'])
  ->parameters(['torneios' => 'tournament'])
  ->names('tournament');

Route::resource('clubes', Controllers\ClubController::class)
  ->only(['index', 'show', 'edit', 'update'])
  ->parameters(['clubes' => 'club'])
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

Route::resource('s', SeasonController::class)
    ->only(['index', 'show'])
    ->parameters(['s' => 'season'])
    ->names('season');

Route::resource('s.t', SeasonTournamentController::class)
    ->only(['index'])
    ->parameters(['s' => 'season'])
    ->names('season.tournament')
    ->shallow();

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/{slug}', [PageController::class, 'show'])->name('page');

// Route::get('/players/create', 'PlayerController@create')->middleware('admin')->name('create_player');
// Route::put('/players/{user}', 'PlayerController@update')->middleware('admin')->name('update_player');
// Route::get('/players/{user}/edit', 'PlayerController@edit')->middleware('admin')->name('edit_player');
// Route::post('/players', 'PlayerController@store')->middleware('admin')->name('store_player');
// Route::get('/players/{person}', 'PlayerController@show')->name('show_player');
// Route::get('/players', 'PlayerController@index')->name('list_players');

// Route::get('/tournaments/create', 'TournamentController@create')->middleware('admin')->name('create_tournament');
// Route::post('/tournaments', 'TournamentController@store')->middleware('auth')->name('store_tournament');
// Route::get('/tournaments/list', 'TournamentController@index')->name('list_tournaments');

// Route::get('/clubs/list', 'ClubController@index')->name('list_clubs');
// Route::get('{club}', 'ClubController@show')->name('show_club');

Route::post('{club}', 'ClubController@store')->middleware('admin')->name('store_club');
// Route::get('{club}/edit', 'ClubController@edit')->middleware('admin')->name('edit_club');
Route::get('{club}/{squad}/lineup', 'LineupController@edit')->name('edit_lineup');

Route::post('/update-lineup/{lineup}', 'LineupController@update')->name('update_lineup');

Route::get('/games/{game}', 'TournamentGameController@show')->name('show_game');

Route::get('/test-heading', function () {
    $player = \App\Models\Person::find(1);
    $duelant = \App\Models\Person::find(2);

    echo \App\Engines\GameDuelsEngine::headingDuelBetween($player, $duelant, true);
});

Route::get('/test-game/{id}', function ($id) {
    $game = \App\Models\TournamentGame::find($id);

    new \App\Engines\MatchEngine($game);
});

// JUST TO NOT BREAK THE APP
Route::get('not-break/1', fn() => '')->name('show_club_players');
Route::get('not-break/2', fn() => '')->name('quit_job');
