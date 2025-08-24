<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});
Route::resource('/tournaments', Controllers\TournamentController::class);
Route::get('/tournaments', [Controllers\TournamentController::class, 'byNation'])->name('tournaments.by_nation');
Route::get('/ongoing-games', [Controllers\TournamentController::class, 'ongoingGames'])->name('ongoing_games');
Route::get('/games/{game}',  [Controllers\TournamentGameController::class, 'show'])->name('show_game');
// Route::post('/games/{game}/points', 'GameEventController@store')->name('save_score');
