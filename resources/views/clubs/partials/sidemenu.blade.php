<h3>{{ __('Squads') }}</h3>
<ul class="submenu">
    <li class="{{ is_current('show_club', ['club' => $club]) ? 'active' : '' }}">
        <a href="{{ link_route('show_club', ['club' => $club]) }}">{{ __('Club') }}</a>
    </li>
</ul>

{{-- @can('update', $club) --}}
    <h3>{{ __('Lineups') }}</h3>

    <ul class="submenu">
        <li class="{{ is_current('edit_lineup', ['club' => $club, 'squad' => 'senior']) ? 'active' : '' }}">
            <a href="{{ link_route('edit_lineup', ['club' => $club, 'squad' => 'senior']) }}">{{ __('A-Team') }}</a>
        </li>

        <li class="{{ is_current('edit_lineup', ['club' => $club, 'squad' => 'u21']) ? 'active' : '' }}">
            <a href="{{ link_route('edit_lineup', ['club' => $club, 'squad' => 'u21']) }}">{{ __('Reserve / U21') }}</a>
        </li>

        <li class="{{ is_current('edit_lineup', ['club' => $club, 'squad' => 'u19']) ? 'active' : '' }}">
            <a href="{{ link_route('edit_lineup', ['club' => $club, 'squad' => 'u19']) }}">{{ __('U19') }}</a>
        </li>
    </ul>
{{-- @endcan --}}
