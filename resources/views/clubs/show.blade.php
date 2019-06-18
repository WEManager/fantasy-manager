@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>{{ $club->name }} ({{ $club->locale }})</h1>

                @if ($club->manager)
                    <small>{{ __('Manager') }}: {{ optional($club->manager)->name }}</small>
                @else
                    <small>{{ __('No manager') }}.</small>
                @endif


                <h2>{{ __('Games for :Club', ['Club' => $club->name]) }}</h2>
                <table class="table">
                    <tr>
                        <td>{{ __('Competition') }}</td>
                        <td>{{ __('Hometeam') }}</td>
                        <td>- {{ __('Awayteam') }}</td>
                    </tr>
                    @foreach($games as $game)
                        <tr>
                            <td><a href="{{ route('show_tournament', [$game->group->tournament_id]) }}">{{ $game->group->name }}</a></td>
                            <td>
                                <a href="{{ route('show_club', ['club' => $game->hometeam_id]) }}">{{ $game->hometeam->name }}</a>
                            </td>
                            <td>-
                                <a href="{{ route('show_club', ['club' => $game->awayteam_id]) }}">{{ $game->awayteam->name }}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $games->links() }}

            </div>
            <div class="col-md-4">
                @include('clubs.partials.sidemenu')
            </div>
        </div>
    </div>

@endsection