@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create A Tournament') }}</div>

                    <div class="card-body">
                        <CreateTournamentComponent clubs="{{ $clubs }}" action="{{ link_route('store_tournament') }}" token="{{ csrf_token() }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
