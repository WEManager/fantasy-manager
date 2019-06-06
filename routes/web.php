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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/players/create', 'PlayerController@create')->name('create_player');
Route::get('/players/{user}/edit', 'PlayerController@edit')->name('edit_player');
Route::get('/players/{user}', 'PlayerController@show')->name('show_player');
Route::post('/players', 'PlayerController@store')->name('store_player');
Route::put('/players/{user}', 'PlayerController@update')->name('update_player');
Route::get('/players', 'PlayerController@index')->name('list_players');

Route::get('/tournaments/create', 'TournamentController@create')->name('create_tournament');
Route::get('/tournaments/{tournament}', 'TournamentController@show')->name('show_tournament');
Route::post('/tournaments', 'TournamentController@store')->name('store_tournament');
Route::get('/tournaments', 'TournamentController@index')->name('list_tournaments');

Route::get('/games/{game}', 'TournamentGameController@show')->name('show_game');

Route::get('/test-game', function () {

    $engine = new \App\Engines\GameEngine(1,
        [
            90,
            90,
            90,
            90,
            90,
            90,
            90,
            90,
            90,
            90,
            90,
            90,
            90,
            90,
            90,
            90,
        ],
        [
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
        ]
    );

    echo $engine->renderEvents();

});