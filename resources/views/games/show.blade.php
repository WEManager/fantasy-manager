@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Game #{{ $game->id }} - A game of {{ $game->group->name }}
                        in {{ $game->group->tournament->name }}</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <game-results gameId="{{ $game->id }}" playerName="{{ $game->playerOne->name }}" points="{{ $game->first_player_score }}" player="player_one"/>
                            </div>
                            <div class="col-sm-6">
                                <h1>{{ $game->playerTwo->name }}</h1>
                                <h1>{{ 501 - $game->second_player_score }}</h1>
                                <table class="table table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Darts</th>
                                        <th>Points</th>
                                        <th>Left</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $left = 501; ?>
                                    @foreach($game->playerTwoEvents as $event)
                                        <tr>
                                            <td>3</td>
                                            <td>{{ $event->value }}</td>
                                            <td>{{ $left - $event->value }}</td>
                                        </tr>
                                        <?php $left = $left - $event->value; ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection