@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="col-xs-12">
            <h1>{{ $person->firstname }} {{ $person->lastname }}</h1>

            <div class="row">
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
            </div>
        </div>
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