@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>{{ $club->name }} ({{ $club->locale }}) Lineup</h1>

                <lineup-component
                        action="{{ route('update_lineup', ['lineup' => $lineup]) }}"
                        players="{{ $players }}"
                        positions="{{ json_encode(getPositionsExceptGoalkeeper()) }}"
                        token="{{ csrf_token() }}"

                        lineup="{{ $lineup }}"
                />

            </div>
            <div class="col-md-4">
                @include('clubs.partials.sidemenu')
            </div>
        </div>
    </div>

@endsection