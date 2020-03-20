@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>{{ $quiz->name }}</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">{{ __('Please check again, :wrong of your answers was wrong.', ['wrong' => $errors->count()]) }}</div>
                @endif

                <form action="{{ link_route('license_test_validation', ['licenseQuiz' => $quiz]) }}" method="post">
                    {{ csrf_field() }}
                    @foreach($quiz->questions as $question)
                        <div class="card" style="margin-bottom: 15px">
                            <div class="card-body">
                                <p><strong>{{ __($question->question) }}</strong></p>

                                @if($errors->any() && $errors->has($question->name))
                                    <div class="alert alert-danger">{{ __('Wrong answer.') }}</div>
                                @elseif ($errors->any())
                                    <div class="alert alert-success">{{ __('Correct answer.') }}</div>
                                @endif

                                @foreach($question->answers as $answer)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="{{ $question->name }}"
                                               id="{{ \Illuminate\Support\Str::slug($answer) }}" value="{{ $answer }}"
                                            {{ old($question->name) == $answer ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label"
                                               for="{{ \Illuminate\Support\Str::slug($answer) }}">
                                            {{ __($answer) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <button class="btn btn-primary">{{ __('Submit your answers') }}</button>
                </form>

            </div>
        </div>
    </div>

@endsection
