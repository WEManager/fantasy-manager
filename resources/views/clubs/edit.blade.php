@extends('layouts.livewire')

@section('title', $club->name . ' - ')

@section('header')
    @include('clubs.partials.header')
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <livewire:edit-club :club="$club" />

            </div>
            <div class="col-md-4">
                @include('clubs.partials.sidemenu')
            </div>
        </div>
    </div>

@endsection
