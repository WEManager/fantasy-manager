@extends('layouts.app')

@section('title', $club->name . ' - ')

@section('header')
    @include('clubs.partials.header')
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <h2>{{ __('Games for :Club', ['Club' => $club->name]) }}</h2>
                <table class="table">
                    <tr>
                        <td>{{ __('Competition') }}</td>
                        <td>{{ __('Date') }}</td>
                        <td>{{ __('Hometeam') }}</td>
                        <td>{{ __('Awayteam') }}</td>
                    </tr>
                    @foreach($games as $game)
                        <tr>
                            <td>
                                @include('tournaments.partials.tournamentname', ['group' => $game->group])
                            </td>
                            <td>
                                {{ date('j/n H:i', strtotime($game->start_time)) }}
                            </td>
                            <td>
                                @include('clubs.partials.clubname', ['club' => $game->hometeam])
                            </td>
                            <td>
                                @include('clubs.partials.clubname', ['club' => $game->awayteam])
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $games->links() }}

            </div>
            <div class="col-md-4">
                @include('clubs.partials.sidemenu')
            </div>
        </div>
    </div>

@endsection
