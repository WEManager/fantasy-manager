@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>{{ $club->name }} ({{ $club->locale }})</h1>

                <p>{{ __('You need to pass the exam for the basic manager license, in order to be able to apply for a
job as a manager in a club. Please be aware of that the questions are mostly there to not get bots taking over teams.
That is why they can seem a bit odd.') }}</p>

                <a class="btn btn-primary" href="{{ link_route('license_test', ['licenseQuiz' => \App\LicenseQuiz::find(1)]) }}">Take Quiz</a>

            </div>
            <div class="col-md-4">
                @include('clubs.partials.sidemenu')
            </div>
        </div>
    </div>

@endsection
