@extends('layouts.app')

@section('title', $club->name . ' - ')

@section('header')
    @include('clubs.partials.header')
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <h2>{{ __('Homegames for :Club', ['Club' => $club->name]) }}</h2>
                <table class="table">
                    <tr>
                        <td>{{ __('Competition') }}</td>
                        <td>{{ __('Status') }}</td>
                        <td>-</td>
                        <td>{{ __('Awayteam') }}</td>
                    </tr>
                    @foreach($club->homegames->take(3) as $game)
                        <tr>
                            <td>
                                @include('tournaments.partials.tournamentname', ['group' => $game->group])
                            </td>
                            <td><a href="{{ link_route('show_game', ['game' => $game]) }}">{!! $game->gameStatus !!}</a>
                            </td>
                            @if ($game->status > 0)
                                <td>
                                    <a href="{{ link_route('show_game', ['game' => $game]) }}">{{ $game->hometeam_score }}
                                        - {{ $game->awayteam_score }}</a></td>
                            @else
                                <td><a href="{{ link_route('show_game', ['game' => $game]) }}">-</a></td>
                            @endif
                            <td>
                                @include('clubs.partials.clubname', ['club' => $game->awayteam])
                            </td>
                        </tr>
                    @endforeach
                </table>

                <h2>{{ __('Awaygames for :Club', ['Club' => $club->name]) }}</h2>
                <table class="table">
                    <tr>
                        <td>{{ __('Competition') }}</td>
                        <td>{{ __('Status') }}</td>
                        <td>-</td>
                        <td>{{ __('Awayteam') }}</td>
                    </tr>
                    @foreach($club->awaygames->take(3) as $game)
                        <tr>
                            <td>
                                @include('tournaments.partials.tournamentname', ['group' => $game->group])
                            </td>
                            <td><a href="{{ link_route('show_game', ['game' => $game]) }}">{!! $game->gameStatus !!}</a>
                            </td>
                            @if ($game->status > 0)
                                <td>
                                    <a href="{{ link_route('show_game', ['game' => $game]) }}">{{ $game->hometeam_score }}
                                        - {{ $game->awayteam_score }}</a></td>
                            @else
                                <td><a href="{{ link_route('show_game', ['game' => $game]) }}">-</a></td>
                            @endif
                            <td>
                                @include('clubs.partials.clubname', ['club' => $game->hometeam])
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="col-md-4">
                @auth()
                    @if (auth()->user()->isAdmin)
                        <a href="{{ link_route('edit_club', ['club' => $club]) }}">Edit Club</a>
                    @endif
                @endauth
                @include('clubs.partials.sidemenu')
            </div>
        </div>
    </div>

@endsection
