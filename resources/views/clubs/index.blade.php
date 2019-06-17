@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>{{ __('List of clubs') }}</h1>
        <div class="row">
            <div class="col-xs-4">

                <ul>
                    @foreach($clubs->take(5) as $club)
                        @include('clubs.jobs.club-list-item')
                    @endforeach
                </ul>

            </div>
            <div class="col-xs-4">

                <ul>
                    @foreach($clubs->slice(5)->take(5) as $club)
                        @include('clubs.jobs.club-list-item')
                    @endforeach
                </ul>

            </div>
            <div class="col-xs-4">

                <ul>
                    @foreach($clubs->slice(10)->take(5) as $club)
                        @include('clubs.jobs.club-list-item')
                    @endforeach
                </ul>

            </div>
        </div>
        <div class="row">
            {{ $clubs->links() }}
        </div>
    </div>

@endsection