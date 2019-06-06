@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $tournament->name }}</div>

                    <div class="card-body">
                        @if ($tournament->type == 'league' || $tournament->type == 'groups')
                            @foreach ($tournament->tournamentGroups as $group)
                                <h2>{{ $group->name }}</h2>
                                <table class="table table-responsive">
                                    <tr>
                                        <td>#ID</td>
                                        <td>Player 1</td>
                                        <td>-</td>
                                        <td>Player 2</td>
                                        <td>P1 Score</td>
                                        <td>P2 Score</td>
                                    </tr>
                                    @foreach($group->games as $game)
                                        <tr>
                                            <td><a href="{{ route('show_game', $game->id) }}">{{ $game->id }}</a></td>
                                            <td>{{ $game->playerOne->name }}</td>
                                            <td>-</td>
                                            <td>{{ $game->playerTwo->name }}</td>
                                            <td>{{ $game->first_player_score }}</td>
                                            <td>{{ $game->second_player_score }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endforeach

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection