@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:tournaments/>
    <livewire:ongoing-games/>

    @auth
        @if(!auth()->user()->club)
            @include('clubs.partials.available-list')
        @endif

        @if (auth()->user()->isAdmin)
            <div class="row">

                <div class="col-sm-6">
                    <a href="{{ link_route('create_player') }}" class="btn btn-primary">{{ __('New Manager') }}</a>
                </div>

                <div class="col-sm-6">
                    <a href="{{ link_route('list_players') }}" class="btn btn-primary">{{ __('Managers') }}</a>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-6">
                    <a href="{{ link_route('create_federation') }}"
                        class="btn btn-primary">{{ __('New Federation') }}</a>
                </div>

                <div class="col-sm-6">
                    <a href="{{ link_route('create_tournament') }}"
                        class="btn btn-primary">{{ __('New Tournament') }}</a>
                </div>

                <div class="col-sm-6">
                    <a href="{{ link_route('list_tournaments') }}"
                        class="btn btn-primary">{{ __('Tournaments') }}</a>
                </div>

            </div>
        @endif
    @endauth
</div>
@endsection
