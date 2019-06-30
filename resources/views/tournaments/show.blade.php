@extends('layouts.app')

@section('title', $tournament->name . ' - ')

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
                                <td>{{ __('+/-') }}</td>
                                <td>{{ __('Points') }}</td>
                            </tr>
                            @foreach ($group->standings as $standing)
                                <tr>
                                    <td>
                                        @include('clubs.partials.clubname', ['club' => $standing->club])
                                    </td>
                                    <td align="center">{{ $standing->won }}</td>
                                    <td align="center">{{ $standing->tie }}</td>
                                    <td align="center">{{ $standing->lost }}</td>
                                    <td align="center">{{ $standing->GoalDifference }}</td>
                                    <td align="right">{{ $standing->points }}</td>
                                </tr>
                            @endforeach
                        </table>

                    </div>

                    <div class="col-sm">
                        <h3>{{ __('Schedule') }}</h3>
                        <table class="table table-responsive">
                            <tr>
                                <td>{{ __('Date') }}</td>
                                <td>{{ __('Status') }}</td>
                                <td>{{ __('Hometeam') }}</td>
                                <td></td>
                                <td>{{ __('Awayteam') }}</td>
                            </tr>
                            @foreach ($group->upcomingGames->take('8') as $game)
                                <tr>
                                    <td>{{ date('j/n H:i', strtotime($game->start_time)) }}</td>
                                    <td>{!! $game->gameStatus !!}</td>
                                    <td>@include('clubs.partials.clubname', ['club' => $game->hometeam])</td>
                                    @if ($game->status > 0)
                                        <td>{{ $game->hometeam_score }} - {{ $game->awayteam_score }}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    <td>@include('clubs.partials.clubname', ['club' => $game->awayteam])</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
