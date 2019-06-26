@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>{{ $club->name }} ({{ $club->locale }})</h1>

                <p>{{ __('You need to pass the exam for the basic manager license, in order to be able to apply for a job as a manager in a club.') }}</p>

                <a href="{{ link_route('license_test', ['LicenseQuiz' => 1]) }}">Take Quiz</a>

            </div>
            <div class="col-md-4">
                @include('clubs.partials.sidemenu')
            </div>
        </div>
    </div>

@endsection
