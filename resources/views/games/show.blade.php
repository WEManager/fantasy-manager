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
                                <game-results gameId="{{ $game->id }}" clubName="{{ $game->hometeam->name }}" score="{{ $game->hometeam_score }}" club="hometeam"/>
                            </div>
                            <div class="col-sm-6">
                                <h1>{{ $game->awayteam->name }}</h1>
                                <h1>{{ $game->awayteam_score }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection