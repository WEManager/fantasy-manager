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
Route::get('/', function () {
    return redirect(app()->getLocale());
});

Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function () {
    Auth::routes();

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/{slug}', 'PageController@show')->name('page');

    Route::get('/players/create', 'PlayerController@create')->name('create_player');
    Route::get('/players/{user}/edit', 'PlayerController@edit')->name('edit_player');
    Route::get('/players/{person}', 'PlayerController@show')->name('show_player');
    Route::post('/players', 'PlayerController@store')->name('store_player');
    Route::put('/players/{user}', 'PlayerController@update')->name('update_player');
    Route::get('/players', 'PlayerController@index')->name('list_players');

    Route::get('/federations/create', 'FederationController@create')->name('create_federation');

    Route::get('/tournaments/create', 'TournamentController@create')->name('create_tournament');
    Route::get('/{tournament}', 'TournamentController@show')->name('show_tournament');
    Route::post('/tournaments', 'TournamentController@store')->middleware('auth')->name('store_tournament');
    Route::get('/tournaments/list', 'TournamentController@index')->name('list_tournaments');

    Route::get('/clubs/list', 'ClubController@index')->name('list_clubs');
    Route::get('{club}', 'ClubController@show')->name('show_club');
    Route::get('{club}/players', 'SquadController@show')->name('show_club_players');
    Route::get('{club}/{squad}/lineup', 'LineupController@edit')->name('edit_lineup');
    Route::post('/update-lineup/{lineup}', 'LineupController@update')->name('update_lineup');

    Route::get('/apply-for-job/{club}', 'ManagerContractController@create')->middleware('auth')->name('apply_for_job');
    Route::post('/apply-for-job', 'ManagerContractController@store')->middleware('auth')->name('send_job_application');
    Route::get('/quit-my-job/{club}', 'ManagerContractController@quitJob')->middleware('auth')->name('quit_job');


    Route::get('/license-test/{licenseQuiz}', 'LicenseQuizController@show')->middleware('level0')->name('license_test');
    Route::post('/license-test/{licenseQuiz}/submit', 'LicenseQuizController@submission')->middleware('level0')->name('license_test_validation');

//Route::get('/match/{game}', '')

    Route::get('/games/{game}', 'TournamentGameController@show')->name('show_game');


    Route::get('/test-heading', function () {
        $player = \App\Person::find(1);
        $duelant = \App\Person::find(2);
        echo \App\Engines\GameDuelsEngine::headingDuelBetween($player, $duelant, true);
    });
    Route::get('/test-game/{id}', function ($locale, $id) {

        $game = \App\TournamentGame::find($id);
        new \App\Engines\MatchEngine($game);
        //dd($game->hometeam->name . ' ' . $game->hometeam_score . ' - ' . $game->awayteam_score . ' ' . $game->awayteam->name);
    });
});
