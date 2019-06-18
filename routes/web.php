<?php

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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


Route::get('/players/create', 'PlayerController@create')->name('create_player');
Route::get('/players/{user}/edit', 'PlayerController@edit')->name('edit_player');
Route::get('/players/{person}', 'PlayerController@show')->name('show_player');
Route::post('/players', 'PlayerController@store')->name('store_player');
Route::put('/players/{user}', 'PlayerController@update')->name('update_player');
Route::get('/players', 'PlayerController@index')->name('list_players');

Route::get('/tournaments/create', 'TournamentController@create')->name('create_tournament');
Route::get('/tournaments/{tournament}', 'TournamentController@show')->name('show_tournament');
Route::post('/tournaments', 'TournamentController@store')->name('store_tournament');
Route::get('/tournaments', 'TournamentController@index')->name('list_tournaments');

Route::get('/clubs', 'ClubController@index')->name('list_clubs');
Route::get('/clubs/{club}', 'ClubController@show')->name('show_club');
Route::get('/clubs/{club}/{squad}', 'SquadController@show')->name('show_club_squad');
Route::get('/clubs/{club}/{squad}/lineup', 'LineupController@edit')->name('edit_lineup');
Route::post('/update-lineup/{lineup}', 'LineupController@update')->name('update_lineup');

Route::get('/apply-for-job/{club}', 'ManagerContractController@create')->name('apply_for_job');
Route::post('/apply-for-job', 'ManagerContractController@store')->name('send_job_application');

//Route::get('/match/{game}', '')

Route::get('/games/{game}', 'TournamentGameController@show')->name('show_game');

Route::get('/test-heading', function() {
    $player = \App\Person::find(1);
    $duelant = \App\Person::find(2);
    echo \App\Engines\GameDuelsEngine::headingDuelBetween($player, $duelant, true);
});
Route::get('/test-game/{id}', function ($id) {

    $game = \App\TournamentGame::find(1);
    $engine = new \App\Engines\MatchEngine($game);
    dd($engine);

    $engine = new \App\Engines\GameEngine($id /*,
        [
            'GK' => \App\Person::find(1),

            'LD' => \App\Person::find(2),
            'CD' => \App\Person::find(3),
            'RD' => \App\Person::find(1),

            'LM' => \App\Person::find(2),
            'CLM' => \App\Person::find(3),
            'CM' => \App\Person::find(1),
            'CRM' => \App\Person::find(2),
            'RM' => \App\Person::find(3),

            'CLF' => \App\Person::find(1),
            'CRF' => \App\Person::find(2),

            'SUB1' => \App\Person::find(3),
            'SUB2' => \App\Person::find(1),
            'SUB3' => \App\Person::find(2),
            'SUB4' => \App\Person::find(3),
            'SUB5' => \App\Person::find(1),

            'tactics' => 70,
        ],
        [
            'GK' => \App\Person::find(1),

            'LD' => \App\Person::find(2),
            'CLD' => \App\Person::find(2),
            'CD' => \App\Person::find(3),
            'CRD' => \App\Person::find(1),
            'RD' => \App\Person::find(1),

            'LM' => \App\Person::find(3),
            'CLM' => \App\Person::find(3),
            'CRM' => \App\Person::find(2),
            'RM' => \App\Person::find(2),

            'CF' => \App\Person::find(1),

            'SUB1' => \App\Person::find(3),
            'SUB2' => \App\Person::find(1),
            'SUB3' => \App\Person::find(2),
            'SUB4' => \App\Person::find(3),
            'SUB5' => \App\Person::find(1),

            'tactics' => 30,
        ]*/
    );

    echo $engine->renderEvents();
});