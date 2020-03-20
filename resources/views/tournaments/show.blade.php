@extends('layouts.app')

@section('title', $tournament->name . ' - ')

@section('header')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1>{{ __($tournament->name) }}</h1>

                @if (count($tournament->tournamentGroups) > 1)
                    <small>{{ __(':Tournament has :Amount groups', ['Tournament' => __($tournament->name),'Amount' => count($tournament->tournamentGroups)]) }}</small>
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
                    <div class="col-sm-12">
                        <h3 id="{{ slugify($group->name) }}">{{ __($group->name) }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td>{{ __('Status') }}</td>
                                    <td>{{ __('Hometeam') }}</td>
                                    <td></td>
                                    <td>{{ __('Awayteam') }}</td>
                                </tr>
                                @for($i = 0; $i<count($group->upcomingGames)/2; $i++)
                                    <tr>
                                        <td>
                                            <a href="{{ link_route('show_game', ['game' => $group->upcomingGames[$i]]) }}">{!! $group->upcomingGames[$i]->gameStatus !!}</a>
                                        </td>
                                        <td>@include('clubs.partials.clubname', ['club' => $participating_clubs[$group->upcomingGames[$i]->hometeam_id]])</td>
                                        @if ($group->upcomingGames[$i]->status > 0)
                                            <td>{{ $group->upcomingGames[$i]->hometeam_score }} - {{ $group->upcomingGames[$i]->awayteam_score }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>@include('clubs.partials.clubname', ['club' => $participating_clubs[$group->upcomingGames[$i]->awayteam_id]])</td>
                                    </tr>
                                @endfor

                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td>{{ __('Status') }}</td>
                                    <td>{{ __('Hometeam') }}</td>
                                    <td></td>
                                    <td>{{ __('Awayteam') }}</td>
                                </tr>
                                @for($i; $i<count($group->upcomingGames); $i++)
                                    <tr>
                                        <td>
                                            <a href="{{ link_route('show_game', ['game' => $group->upcomingGames[$i]]) }}">{!! $group->upcomingGames[$i]->gameStatus !!}</a>
                                        </td>
                                        <td>@include('clubs.partials.clubname', ['club' => $participating_clubs[$group->upcomingGames[$i]->hometeam_id]])</td>
                                        @if ($group->upcomingGames[$i]->status > 0)
                                            <td>{{ $group->upcomingGames[$i]->hometeam_score }} - {{ $group->upcomingGames[$i]->awayteam_score }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>@include('clubs.partials.clubname', ['club' => $participating_clubs[$group->upcomingGames[$i]->awayteam_id]])</td>
                                    </tr>
                                @endfor

                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table">
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
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
