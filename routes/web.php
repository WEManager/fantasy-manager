<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\ClubPlayerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TournamentGameController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/{slug}', [PageController::class, 'show'])->name('page');

Route::resource('c', ClubController::class)
    ->only([ 'index', 'show', 'edit', 'update' ])
    ->parameters(['c' => 'club'])
    ->names('club');

Route::resource('c.p', ClubPlayerController::class)
    ->only([ 'index' ])
    ->parameters(['c' => 'club'])
    ->names('club.player')
    ->shallow();

Route::resource('p', PlayerController::class)
    ->only([ 'index', 'show' ])
    ->parameters(['p' => 'player'])
    ->names('player');

Route::resource('t', TournamentController::class)
    ->only([ 'index', 'show' ])
    ->parameters(['t' => 'tournament'])
    ->names('tournament');

Route::resource('g', TournamentGameController::class)
    ->only([ 'index', 'show' ])
    ->parameters(['g' => 'game'])
    ->names('game');

Route::get('/players/create', 'PlayerController@create')->middleware('admin')->name('create_player');
Route::put('/players/{user}', 'PlayerController@update')->middleware('admin')->name('update_player');
Route::get('/players/{user}/edit', 'PlayerController@edit')->middleware('admin')->name('edit_player');
Route::post('/players', 'PlayerController@store')->middleware('admin')->name('store_player');
// Route::get('/players/{person}', 'PlayerController@show')->name('show_player');
// Route::get('/players', 'PlayerController@index')->name('list_players');

Route::get('/federations/create', 'FederationController@create')->name('create_federation');

Route::get('/tournaments/create', 'TournamentController@create')->middleware('admin')->name('create_tournament');
Route::post('/tournaments', 'TournamentController@store')->middleware('auth')->name('store_tournament');
// Route::get('/{tournament}', 'TournamentController@show')->name('show_tournament');
// Route::get('/tournaments/list', 'TournamentController@index')->name('list_tournaments');

// Route::get('/clubs/list', 'ClubController@index')->name('list_clubs');
// Route::get('{club}', 'ClubController@show')->name('show_club');
// Route::get('{club}/players', 'SquadController@show')->name('show_club_players');
Route::post('{club}', 'ClubController@store')->middleware('admin')->name('store_club');
Route::get('{club}/edit', 'ClubController@edit')->middleware('admin')->name('edit_club');
Route::get('{club}/{squad}/lineup', 'LineupController@edit')->name('edit_lineup');

Route::post('/update-lineup/{lineup}', 'LineupController@update')->name('update_lineup');

Route::get('/apply-for-job/{club}', 'ManagerContractController@create')->middleware('auth')->name('apply_for_job');
Route::post('/apply-for-job', 'ManagerContractController@store')->middleware('auth')->name('send_job_application');
Route::get('/quit-my-job/{club}', 'ManagerContractController@quitJob')->middleware('auth')->name('quit_job');


Route::get('/license-test/{licenseQuiz}', 'LicenseQuizController@show')->middleware('level0')->name('license_test');
Route::post('/license-test/{licenseQuiz}/submit', 'LicenseQuizController@submission')->middleware('level0')->name('license_test_validation');

// Route::get('/games/{game}', 'TournamentGameController@show')->name('show_game');

Route::get('/test-heading', function () {
    $player = \App\Models\Person::find(1);
    $duelant = \App\Models\Person::find(2);

    echo \App\Engines\GameDuelsEngine::headingDuelBetween($player, $duelant, true);
});

Route::get('/test-game/{id}', function ($id) {
    $game = \App\Models\TournamentGame::find($id);

    new \App\Engines\MatchEngine($game);
});

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
