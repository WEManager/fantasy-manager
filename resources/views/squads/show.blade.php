@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>{{ $club->name }} ({{ $club->locale }})</h1>

                <table class="table">
                    <thead>
                    <tr>
                        <td>{{ __('Firstname') }}</td>
                        <td>{{ __('Lastname') }}</td>
                        <td>{{ __('Age') }}</td>
                        <td>{{ __('Nationality') }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($players as $player)
                        <tr>
                            <td>
                                <a href="{{ route('show_player', ['person' => $player->person_id]) }}">{{ $player->firstname }}</a>
                            </td>
                            <td>
                                <a href="{{ route('show_player', ['person' => $player->person_id]) }}">{{ $player->lastname }}</a>
                            </td>
                            <td>{{ $player->age }}</td>
                            <td>{{ $player->nationality }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            <div class="col-md-4">
                <ul>
                    <li><a href="/clubs/{{$club->id}}/senior">Senior</a></li>
                    <li><a href="/clubs/{{$club->id}}/u21">Reserv / U21</a></li>
                    <li><a href="/clubs/{{$club->id}}/u19">U19</a></li>
                </ul>
            </div>
        </div>
    </div>

@endsection