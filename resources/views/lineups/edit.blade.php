@extends('layouts.livewire')

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
            <div class="col-md-12">

                <livewire:edit-lineup :players="$players" :lineup="$lineup" />

            </div>
        </div>
    </div>

@endsection
