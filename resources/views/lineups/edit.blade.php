@extends('layouts.app')

@section('header')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1>{{ $club->name }} {{ strtoupper($squad) }} {{ __('Lineup') }}</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

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