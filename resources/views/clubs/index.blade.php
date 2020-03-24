@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>{{ __('List of clubs') }}</h1>
        <div class="row">
            <div class="col-sm-12">

                <ul>
                    @foreach($clubs as $club)
                        <li>{{ $club->name }}</li>
                    @endforeach
                </ul>

            </div>
        </div>
        <div class="row">
            {{ $clubs->links() }}
        </div>
    </div>

@endsection
