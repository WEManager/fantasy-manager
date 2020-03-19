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
                                <td>{{ __('Pos.') }}</td>
                                <td>{{ __('Club') }}</td>
                                <td>{{ __('Games') }}</td>
                                <td>{{ __('Won') }}</td>
                                <td>{{ __('Ties') }}</td>
                                <td>{{ __('Lost') }}</td>
                                <td>{{ __('+/-') }}</td>
                                <td>{{ __('Points') }}</td>
                            </tr>
                            <?php $i = 1; ?>
                            @foreach ($group->standings as $standing)
                                <tr class="{{ $position_status[$i-1] }}" title="{{ $position_status[$i-1] }}">
                                    <td>{{ $i }}</td>
                                    <td>
                                        @include('clubs.partials.clubname', ['club' => $standing->club])
                                    </td>
                                    <td align="center">{{ $standing->won + $standing->tie + $standing->lost }}</td>
                                    <td align="center">{{ $standing->won }}</td>
                                    <td align="center">{{ $standing->tie }}</td>
                                    <td align="center">{{ $standing->lost }}</td>
                                    <td align="center">{{ $standing->GoalDifference }}</td>
                                    <td align="right">{{ $standing->points }}</td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        </table>

                    </div>

                    <div class="col-sm">
                        <h3>{{ __('Schedule') }}</h3>
                        <table class="table table-responsive">
                            <tr>
                                <td>{{ __('Status') }}</td>
                                <td>{{ __('Hometeam') }}</td>
                                <td></td>
                                <td>{{ __('Awayteam') }}</td>
                            </tr>
                            @foreach ($group->upcomingGames->take('8') as $game)
                                <tr>
                                    <td>
                                        <a href="{{ link_route('show_game', ['game' => $game]) }}">{!! $game->gameStatus !!}</a>
                                    </td>
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
