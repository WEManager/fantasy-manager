<div>
    @if ($ongoing->count())
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Ongoing games') }}

                        <!--<form class="form-inline">
                            <div class="input-group mb-2 mr-sm-2">
                                <label for="limit">{{ __('Per Page') }}: </label>
                                <select wire:model="limit" class="form-control" name="limit" id="limit">
                                    @for ($i=1;$i<=25;$i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </form>-->
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" style="margin-bottom: 0">
                                @foreach ($ongoing as $game)
                                    <tr>
                                        <td style="border: none">
                                            @include('tournaments.partials.tournamentname', ['group' => $game->group])
                                        </td>
                                        <td style="border: none">
                                            <a href="{{ link_route('show_game', ['game' => $game]) }}">{!! $game->GameStatus  !!}</a>
                                        </td>
                                        <td style="border: none">
                                            @include('clubs.partials.clubname', ['club' => $game->hometeam])
                                        </td>
                                        <td style="border: none">
                                            <a href="{{ link_route('show_game', ['game' => $game]) }}">{{ $game->hometeam_score }}
                                                - {{ $game->awayteam_score }}</a></td>
                                        <td style="border: none">
                                            @include('clubs.partials.clubname', ['club' => $game->awayteam])
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $ongoing->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
