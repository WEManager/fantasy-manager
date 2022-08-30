<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <table class="table">
                <thead>
                <tr>
                    <td>{{ __('Firstname') }}</td>
                    <td>{{ __('Lastname') }}</td>
                    <td>{{ __('Age') }}</td>
                    <td>{{ __('Nationality') }}</td>
                </tr>
                </thead>
                <tbody>
                @foreach($players as $player)
                    <tr>
                        <td>
                            <a href="{{ link_route('player.show', ['person' => $player->person_id]) }}">{{ $player->firstname }}</a>
                        </td>
                        <td>
                            <a href="{{ link_route('player.show', ['person' => $player->person_id]) }}">{{ $player->lastname }}</a>
                        </td>
                        <td>{{ $player->age }}</td>
                        <td><span class="flag-icon flag-icon-{{ strtolower($player->nationality) }}"></span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h3>{{ __('Squads') }}</h3>
            <ul class="submenu">
                <li><a href="#" wire:click="updateSquad('senior')" class="{{ $squad == 'senior' ? 'active' : '' }}">{{ __('A-Team') }}</a></li>
                <li><a href="#" wire:click="updateSquad('u21')" class="{{ $squad == 'u21' ? 'active' : '' }}">{{ __('U21') }}</a></li>
                <li><a href="#" wire:click="updateSquad('u19')" class="{{ $squad == 'u19' ? 'active' : '' }}">{{ __('U19') }}</a></li>
            </ul>
        </div>
        {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    </div>
</div>
