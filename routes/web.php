<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', 'HomeController@index')->name('home');

Route::get('/{slug}', 'PageController@show')->name('page');

Route::get('/players/create', 'PlayerController@create')->middleware('admin')->name('create_player');
Route::get('/players/{user}/edit', 'PlayerController@edit')->middleware('admin')->name('edit_player');
Route::get('/players/{person}', 'PlayerController@show')->name('show_player');
Route::post('/players', 'PlayerController@store')->middleware('admin')->name('store_player');
Route::put('/players/{user}', 'PlayerController@update')->middleware('admin')->name('update_player');
Route::get('/players', 'PlayerController@index')->name('list_players');

Route::get('/federations/create', 'FederationController@create')->name('create_federation');

Route::get('/tournaments/create', 'TournamentController@create')->middleware('admin')->name('create_tournament');
Route::get('/{tournament}', 'TournamentController@show')->name('show_tournament');
Route::post('/tournaments', 'TournamentController@store')->middleware('auth')->name('store_tournament');
Route::get('/tournaments/list', 'TournamentController@index')->name('list_tournaments');

Route::get('/clubs/list', 'ClubController@index')->name('list_clubs');
Route::get('{club}', 'ClubController@show')->name('show_club');
Route::get('{club}/edit', 'ClubController@edit')->middleware('admin')->name('edit_club');
Route::post('{club}', 'ClubController@store')->middleware('admin')->name('store_club');
Route::get('{club}/players', 'SquadController@show')->name('show_club_players');
Route::get('{club}/{squad}/lineup', 'LineupController@edit')->name('edit_lineup');
Route::post('/update-lineup/{lineup}', 'LineupController@update')->name('update_lineup');

Route::get('/apply-for-job/{club}', 'ManagerContractController@create')->middleware('auth')->name('apply_for_job');
Route::post('/apply-for-job', 'ManagerContractController@store')->middleware('auth')->name('send_job_application');
Route::get('/quit-my-job/{club}', 'ManagerContractController@quitJob')->middleware('auth')->name('quit_job');


Route::get('/license-test/{licenseQuiz}', 'LicenseQuizController@show')->middleware('level0')->name('license_test');
Route::post('/license-test/{licenseQuiz}/submit', 'LicenseQuizController@submission')->middleware('level0')->name('license_test_validation');

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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
