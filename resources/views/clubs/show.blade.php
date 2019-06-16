@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>{{ $club->name }} ({{ $club->locale }})</h1>

                <p>Lorem ipsum...</p>

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