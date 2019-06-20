@extends('layouts.app')

@section('header')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1>{{ $club->name }} {{ strtoupper($squad) }} ({{ $club->locale }})</h1>

                @if ($club->manager)
                    <small>{{ __('Manager') }}: {{ optional($club->manager)->name }}</small>
                @else
                    <small>{{ __('No manager') }}.</small>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>{{ $club->name }} ({{ $club->locale }})</h1>

                <table class="table">
                    <thead>
                    <tr>
                        <td>{{ __('Firstname') }}</td>
                        <td>{{ __('Lastname') }}</td>
                        <td>{{ __('Age') }}</td>
                        <td>{{ __('Nationality') }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($players as $player)
                        <tr>
                            <td>
                                <a href="{{ route('show_player', ['person' => $player->person_id]) }}">{{ $player->firstname }}</a>
                            </td>
                            <td>
                                <a href="{{ route('show_player', ['person' => $player->person_id]) }}">{{ $player->lastname }}</a>
                            </td>
                            <td>{{ $player->age }}</td>
                            <td>{{ $player->nationality }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            <div class="col-md-4">
                @include('clubs.partials.sidemenu')
            </div>
        </div>
    </div>

@endsection