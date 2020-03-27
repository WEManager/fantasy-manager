@extends('layouts.livewire')

@section('title', $tournament->name . ' - ')

@section('header')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1>{{ __($tournament->name) }}</h1>

                {{ $tournament->status }}
                <h4>{{ __('Ongoing between :start and :end', ['start' => $tournament->start_date,'end' => $tournament->end_date]) }}</h4>

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
                <livewire:fixtures-by-date :groupId="$group->id"/>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td>{{ __('Pos.') }}</td>
                                    <td>{{ __('Club') }}</td>
                                    <td align="center">{{ __('Games') }}</td>
                                    <td align="center">{{ __('Won') }}</td>
                                    <td align="center">{{ __('Ties') }}</td>
                                    <td align="center">{{ __('Lost') }}</td>
                                    <td align="center">{{ __('+/-') }}</td>
                                    <td align="right">{{ __('Points') }}</td>
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
