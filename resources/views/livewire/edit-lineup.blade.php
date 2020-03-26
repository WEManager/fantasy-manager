<div class="row">
    <div class="col-12">
        @if($errors->has('too_many_players'))
            <span>{{ $errors->first('too_many_players') }}</span>
        @endif
    </div>
    <div class="col-12">
        <button class="btn btn-primary" wire:click="saveLineup">Save</button>
        <button class="btn" wire:click="resetLineup">Cancel</button>
    </div>
    <div class="col-lg-8">
        <div class="pitch">
            @for($i=1;$i<12;$i++)
                @if (isset($players[${"player_$i"}]))
                    <div class="player {{ strtolower(${"position_$i"}) }}">
                        <div class="name">
                            {{ $players[${"player_$i"}]['firstname'] }} {{ $players[${"player_$i"}]['lastname'] }}
                        </div>
                    </div>
                @endif
            @endfor
        </div>

        <h6>{{ __('Substitutes') }}</h6>
        <div class="bench d-flex justify-content-between">
            @for($i=1;$i<7;$i++)
                @if (isset($players[${"substitute_$i"}]) && $players[${"substitute_$i"}] != null)
                    <div class="player">
                        <div class="name">
                            {{ $players[${"substitute_$i"}]['firstname'] }} {{ $players[${"substitute_$i"}]['lastname'] }}
                        </div>
                    </div>
                @endif
            @endfor
        </div>
    </div>
    <div class="col-lg-4">
        {{-- Do your work, then step back. --}}
        <table>
            @foreach($players as $player)
                <tr>
                    <td>
                        <select wire:change="positionChange($event.target.value, '{{ $player['id'] }}')" name="position"
                                id="position_of_{{ $player['id'] }}">
                            <option value=""
                                    @if (!isset($player['selected_position']) || $player['selected_position'] == '')
                                    selected="selected"
                                @endif>--
                            </option>
                            @foreach(getPositions() as $position)
                                <option
                                    @if (isset($player['selected_position']) && $player['selected_position'] == $position)
                                    selected="selected"
                                    @endif
                                    value="{{ $position }}">{{ $position }}</option>
                            @endforeach
                            @for($i=1;$i<7;$i++)
                                <option
                                    @if (isset(${"substitute_$i"}) && ${"substitute_$i"} == $player['id'])
                                    selected="selected"
                                    @endif
                                    value="{{ 'substitute_'. $i }}">{{ "SUB $i" }}</option>
                            @endfor
                        </select>
                    </td>
                    <td>{{ $player['firstname'] }} {{ $player['lastname'] }}</td>
                    <td>{{ $player['age'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
