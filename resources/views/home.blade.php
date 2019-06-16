@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-sm-6">
                <a href="{{ route('create_player') }}" class="btn btn-primary">{{ __('New Manager') }}</a>
            </div>

            <div class="col-sm-6">
                <a href="{{ route('list_players') }}" class="btn btn-primary">{{ __('Managers') }}</a>
            </div>

        </div>
        <div class="row">

            <div class="col-sm-6">
                <a href="{{ route('create_tournament') }}" class="btn btn-primary">{{ __('New Tournament') }}</a>
            </div>

            <div class="col-sm-6">
                <a href="{{ route('list_tournaments') }}" class="btn btn-primary">{{ __('Tournaments') }}</a>
            </div>

        </div>
@endsection
