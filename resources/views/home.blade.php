@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>{{ __('List of available clubs') }}</h1>
        <div class="row">
            <div class="col-xs-4">

                <ul>
                    @foreach($clubs->take(30) as $club)
                        @include('clubs.jobs.club-list-item')
                    @endforeach
                </ul>

            </div>
            <div class="col-xs-4">

                <ul>
                    @foreach($clubs->slice(30)->take(30) as $club)
                        @include('clubs.jobs.club-list-item')
                    @endforeach
                </ul>

            </div>
            <div class="col-xs-4">

                <ul>
                    @foreach($clubs->slice(60)->take(30) as $club)
                        @include('clubs.jobs.club-list-item')
                    @endforeach
                </ul>

            </div>
        </div>

        @if (auth()->check() && auth()->user()->isAdmin())
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
        @endif
    </div>
@endsection
