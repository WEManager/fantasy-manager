@extends('layouts.app')


@section('header')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1>{{ $tournament->name }}</h1>

                @if (count($tournament->tournamentGroups) > 1)
                    <small>{{ __(':Tournament has :Amount groups', ['Tournament' => $tournament->name,'Amount' => count($tournament->tournamentGroups)]) }}</small>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="container">
        <div class="col-xs-12">


            @foreach($tournament->tournamentGroups as $group)
                <div class="row">
                    <div class="col-sm">

                        <h3 id="{{ slugify($group->name) }}">{{ $group->name }}</h3>
                        <table class="table table-responsive">
                            <tr>
                                <td>{{ __('Club') }}</td>
                                <td>{{ __('Won') }}</td>
                                <td>{{ __('Ties') }}</td>
                                <td>{{ __('Lost') }}</td>
                                <td>{{ __('Points') }}</td>
                            </tr>
                            @foreach ($group->standings as $standing)
                                <tr>
                                    <td>
                                        @include('clubs.partials.clubname', ['club' => $standing->club])
                                    </td>
                                    <td>{{ $standing->won }}</td>
                                    <td>{{ $standing->tie }}</td>
                                    <td>{{ $standing->lost }}</td>
                                    <td>{{ $standing->points }}</td>
                                </tr>
                            @endforeach
                        </table>

                    </div>

                    <div class="col-sm">
                        <h3>{{ __('Schedule') }}</h3>
                        <table class="table table-responsive">
                            <tr>
                                <td>ID</td>
                                <td>{{ __('Hometeam') }}</td>
                                <td></td>
                                <td>{{ __('Awayteam') }}</td>
                            </tr>
                            @foreach ($group->upcomingGames->take('8') as $game)
                                <tr>
                                    <td>{{ date('j/n H:i', strtotime($game->start_time)) }}</td>
                                    <td>@include('clubs.partials.clubname', ['club' => $game->hometeam])</td>
                                    <td>-</td>
                                    <td>@include('clubs.partials.clubname', ['club' => $game->awayteam])</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endforeach

            {{--<div class="row">
                <div class="col-xs-4">
                    <ul>
                        <li>{{ __($person->nationality) }}</li>
                        <li>{{ __(':Age years old', ['Age' => $person->age]) }}</li>
                    </ul>
                </div>

                <div class="col-xs-4">
                    <ul>
                        <li>{{ __('Contract with :Club', ['Club' => $person->club->name]) }}</li>
                        <li>{{ __(':Wage per month until :Until', ['Wage' => $person->contract->wage, 'Until' => date('j/n Y', strtotime($person->contract->until))]) }}</li>
                    </ul>
                </div>
            </div>--}}
        </div>
    </div>
    {{--<div class="container">
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
                                        <td>Hometeam</td>
                                        <td>-</td>
                                        <td>Awayteam</td>
                                        <td>P1 Score</td>
                                        <td>P2 Score</td>
                                    </tr>
                                    @foreach($group->games as $game)
                                        <tr>
                                            <td><a href="{{ route('show_game', $game->id) }}">{{ $game->id }}</a></td>
                                            <td>{{ $game->hometeam->name }}</td>
                                            <td>-</td>
                                            <td>{{ $game->awayteam->name }}</td>
                                            <td>{{ $game->hometeam_score }}</td>
                                            <td>{{ $game->awayteam_score }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endforeach

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>--}}

@endsection