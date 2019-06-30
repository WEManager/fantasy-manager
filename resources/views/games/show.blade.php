@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                {{ $game->gameStatus }} <br>
                @include('tournaments.partials.tournamentname', ['group' => $game->group])
            </div>
        </div>
        <div class="row">
            <div class="col-5 text-left">
                <h2>
                    <a href="{{ link_route('show_club', ['club' => $game->hometeam]) }}">{{ $game->hometeam->name }}</a>
                </h2>
            </div>
            <div class="col-2 text-center">
                {{ $game->hometeam_score }}
                -
                {{ $game->awayteam_score }}
            </div>
            <div class="col-5 text-right">
                <h2>
                    <a href="{{ link_route('show_club', ['club' => $game->awayteam]) }}">{{ $game->awayteam->name }}</a>
                </h2>
            </div>
        </div>
        {{-- Show progressbar if game is active --}}
        @if ($game->status == '1')
            <div class="row">
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-3">
                <table>
                    @foreach ($hometeam as $key => $value)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>
                                <a href="{{ link_route('show_player', ['person' => $value]) }}">{{ $value['firstname'] . ' ' . $value['lastname'] }}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col-6">
                @foreach($game->gameHappenings as $happening)
                    {{ $happening->id }}
                @endforeach
            </div>
            <div class="col-3">
                <table>
                    @foreach ($awayteam as $key => $value)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>
                                <a href="{{ link_route('show_player', ['person' => $value]) }}">{{ $value['firstname'] . ' ' . $value['lastname'] }}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>



@endsection
