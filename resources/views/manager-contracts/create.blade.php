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


                <form action="{{ route('send_job_application') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="club_id" value="{{ $club->id }}">
                    <div class="form-group row">
                        <label for="from" class="col-sm-2 col-form-label">{{ __('Starting date') }}:</label>
                        <div class="col-sm-10">
                            <input name="from" type="date" class="form-control" id="from" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="until" class="col-sm-2 col-form-label">{{ __('Ending date') }}:</label>
                        <div class="col-sm-10">
                            <input name="until" type="date" class="form-control" id="until" value="{{ date('Y-m-d', strtotime('+9 months')) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="wage" class="col-sm-2 col-form-label">{{ __('Wage') }}:</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input name="wage" type="number" class="form-control" id="wage" value="100">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">{{ __('per month') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2 order-1"></div>
                        <div class="col-sm-10 order-2">
                            <button type="submit" class="btn btn-primary">{{ __('Send application') }}</button>
                        </div>
                    </div>
                </form>

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