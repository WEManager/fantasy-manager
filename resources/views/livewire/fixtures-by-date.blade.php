<div>
    <div class="row">
        <div class="col-sm-12">
            <h2>{{ __('Games on :date', ['date' => $date]) }}</h2>
            <div class="row">
                <div class="col-sm-6">
                    <button wire:click="goToPreviousDay">{{ $before }}</button>
                </div>
                <div class="col-sm-6 text-right">
                    <button wire:click="goToNextDay">{{ $after }}</button>
                </div>
            </div>
            @if ($fixtures->count())
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>{{ __('Status') }}</td>
                            <td>{{ __('Hometeam') }}</td>
                            <td></td>
                            <td>{{ __('Awayteam') }}</td>
                        </tr>
                        @for($i = 0; $i<count($fixtures); $i++)
                            <tr>
                                <td>
                                    <a href="{{ link_route('show_game', ['game' => $fixtures[$i]]) }}">{!! $fixtures[$i]->gameStatus !!}</a>
                                </td>
                                <td>@include('clubs.partials.clubname', ['club' => $fixtures[$i]->hometeam])</td>
                                @if ($fixtures[$i]->status > 0)
                                    <td>{{ $fixtures[$i]->hometeam_score }} - {{ $fixtures[$i]->awayteam_score }}</td>
                                @else
                                    <td>-</td>
                                @endif
                                <td>@include('clubs.partials.clubname', ['club' => $fixtures[$i]->awayteam])</td>
                            </tr>
                        @endfor

                    </table>
                </div>
            @else
                <p>{{ __('No games on :date', ['date' => $date]) }}</p>
            @endif
        </div>
    </div>
</div>
