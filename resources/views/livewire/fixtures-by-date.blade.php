<div>
    <div class="row">
        <div class="col-sm-12">
            <h2>{{ __('Games on :date', ['date' => $date]) }}</h2>
            <div class="row" style="margin-bottom: 15px">
                <div class="col-6">
                    <button class="btn btn-primary" wire:click="goToPreviousDay"><svg class="bi bi-chevron-double-left" width=".8em" height=".8em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 010 .708L2.707 8l5.647 5.646a.5.5 0 01-.708.708l-6-6a.5.5 0 010-.708l6-6a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M12.354 1.646a.5.5 0 010 .708L6.707 8l5.647 5.646a.5.5 0 01-.708.708l-6-6a.5.5 0 010-.708l6-6a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                        </svg> {{ $before }}</button>
                </div>
                <div class="col-6 text-right">
                    <button class="btn btn-primary" wire:click="goToNextDay">{{ $after }} <svg class="bi bi-chevron-double-right" width=".8em" height=".8em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 01.708 0l6 6a.5.5 0 010 .708l-6 6a.5.5 0 01-.708-.708L9.293 8 3.646 2.354a.5.5 0 010-.708z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 01.708 0l6 6a.5.5 0 010 .708l-6 6a.5.5 0 01-.708-.708L13.293 8 7.646 2.354a.5.5 0 010-.708z" clip-rule="evenodd"/>
                        </svg></button>
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
