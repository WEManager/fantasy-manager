@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <table class="table">
                    <thead>
                    <tr>
                        <td><strong>{{ __('Name') }}</strong></td>
                        <td><strong>{{ __('Edit') }}</strong></td>
                        <td><strong>{{ __('Show') }}</strong></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($players as $player)
                        <tr>
                            <td>{{ $player->name }}</td>
                            <td><a href="{{ link_route('edit_player', ['user' => $player]) }}">{{ __('Edit') }}</a></td>
                            <td><a href="{{ link_route('show_player', ['user' => $player]) }}">{{ __('Show') }}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection