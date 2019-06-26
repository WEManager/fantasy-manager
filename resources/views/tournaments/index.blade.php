@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tournaments</div>

                    <div class="card-body">
                        <table class="table table-responsive">
                            <tr>
                                <td>#ID</td>
                                <td>Name</td>
                            </tr>
                            @foreach ($tournaments as $tournament)
                                <tr>
                                    <td>
                                        <a href="{{ link_route('show_tournament', ['tournament' => $tournament->id]) }}">{{ $tournament->id }}</a>
                                    </td>
                                    <td>
                                        <a href="{{ link_route('show_tournament', ['tournament' => $tournament->id]) }}">{{ $tournament->name }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection