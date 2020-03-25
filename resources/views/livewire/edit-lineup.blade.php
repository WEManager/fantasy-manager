<div class="row">
    <div class="col-lg-8">
        <div class="pitch">
            @for($i=1;$i<12;$i++)
                <div class="player {{ strtolower($lineup->{"position_$i"}) }}">
                    <div class="name">{{ $players[$lineup->{"player_$i"}]->firstname }} {{ $players[$lineup->{"player_$i"}]->lastname }}</div>
                </div>
            @endfor
        </div>
        <ul>
            @for($i=1;$i<7;$i++)
                <li>{{ "SUB $i" }}
                    : {{ $players[$lineup->{"substitute_$i"}]->firstname }} {{ $players[$lineup->{"substitute_$i"}]->lastname }}</li>
            @endfor
        </ul>
    </div>
    <div class="col-lg-4">
        {{-- Do your work, then step back. --}}
        <table>
            @foreach($players as $player)
                <tr>
                    <td>
                        <select wire:model="testChange" name="position" id="position_of_{{ $player->id }}">
                            <option>--</option>
                            @foreach(getPositions() as $position)
                                <option
                                    @if (isset($player->selected_position) && $player->selected_position == $position)
                                    selected="selected"
                                    @endif
                                    value="{{ $position }}">{{ $position }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>{{ $player->firstname }} {{ $player->lastname }}</td>
                    <td>{{ $player->age }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
