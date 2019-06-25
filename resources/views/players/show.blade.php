@extends('layouts.app')

@section('header')
    <div class="club--header" style="background-color: {{ $person->club->club_colors[0] }}">

        <div class="container py-4">
            <div class="col-xs-12">
                <h1
                        style="
                                color: {{ $person->club->club_colors[1] }};
                                text-shadow: 1px 1px 0 {{ $person->club->club_colors[2] }},-1px 1px 0 {{ $person->club->club_colors[2] }},1px -1px 0 {{ $person->club->club_colors[2] }},-1px -1px 0 {{ $person->club->club_colors[2] }},0px 1px 0 {{ $person->club->club_colors[2] }},0px -1px 0 {{ $person->club->club_colors[2] }},-1px 0px 0 {{ $person->club->club_colors[2] }},1px 0px 0 {{ $person->club->club_colors[2] }},2px 2px 0 {{ $person->club->club_colors[2] }},-2px 2px 0 {{ $person->club->club_colors[2] }},2px -2px 0 {{ $person->club->club_colors[2] }},-2px -2px 0 {{ $person->club->club_colors[2] }},0px 2px 0 {{ $person->club->club_colors[2] }},0px -2px 0 {{ $person->club->club_colors[2] }},-2px 0px 0 {{ $person->club->club_colors[2] }},2px 0px 0 {{ $person->club->club_colors[2] }},1px 2px 0 {{ $person->club->club_colors[2] }},-1px 2px 0 {{ $person->club->club_colors[2] }},1px -2px 0 {{ $person->club->club_colors[2] }},-1px -2px 0 {{ $person->club->club_colors[2] }},2px 1px 0 {{ $person->club->club_colors[2] }},-2px 1px 0 {{ $person->club->club_colors[2] }},2px -1px 0 {{ $person->club->club_colors[2] }},-2px -1px 0 {{ $person->club->club_colors[2] }};
                                ">{{ $person->firstname }} {{ $person->lastname }}</h1>

                <table style="color: {{ $person->club->club_colors[1] }};">
                    <tr>
                        <td>
                            <span class="flag-icon flag-icon-{{ strtolower($person->nationality) }}"></span>
                        </td>
                        <td>
                            {{ __('Contract with :Club', ['Club' => $person->club->name]) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ __(':Age years old', ['Age' => $person->age]) }}
                        </td>
                        <td>
                            {{ __(':Wage per month until :Until', ['Wage' => $person->contract->wage, 'Until' => date('j/n Y', strtotime($person->contract->until))]) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="container">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-sm-3">
                    <h3>{{ __('Technical') }}</h3>
                    <table class="table">
                        @foreach ($person->technical as $key => $value)
                            <tr>
                                <td>{{ __($key) }}</td>
                                <td align="right">{{ round($value/5) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="col-sm-3">
                    <h3>{{ __('Mental') }}</h3>
                    <table class="table">
                        @foreach ($person->mental as $key => $value)
                            <tr>
                                <td>{{ __($key) }}</td>
                                <td align="right">{{ round($value/5) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="col-sm-3">
                    <h3>{{ __('Physical') }}</h3>
                    <table class="table">
                        @foreach ($person->physical as $key => $value)
                            <tr>
                                <td>{{ __($key) }}</td>
                                <td align="right">{{ round($value/5) }}</td>
                            </tr>
                        @endforeach
                    </table>
                    <hr>
                    <table class="table">
                        <tr>
                            <td>{{ __('Length') }}</td>
                            <td align="right">{{ $person->length }} cm</td>
                        </tr>
                        <tr>
                            <td>{{ __('Weight') }}</td>
                            <td align="right">{{ $person->weight }} kg</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection