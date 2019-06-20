<h3>{{ __('Squads') }}</h3>
<ul>
    <li><a href="{{ route('show_club', ['club' => $club]) }}">{{ __('Club') }}</a></li>
    <li><a href="{{ route('show_club', ['club' => $club]) }}/senior">{{ __('A-Team') }}</a></li>
    <li><a href="{{ route('show_club', ['club' => $club]) }}/u21">{{ __('Reserve / U21') }}</a></li>
    <li><a href="{{ route('show_club', ['club' => $club]) }}/u19">{{ __('U19') }}</a></li>
</ul>
@can('update', $club)
    <h3>{{ __('Lineups') }}</h3>
    <ul>
        <li><a href="{{ route('edit_lineup', ['club' => $club, 'squad' => 'senior']) }}">{{ __('A-Team') }}</a></li>
        <li><a href="{{ route('edit_lineup', ['club' => $club, 'squad' => 'u21']) }}">{{ __('Reserve / U21') }}</a></li>
        <li><a href="{{ route('edit_lineup', ['club' => $club, 'squad' => 'u19']) }}">{{ __('U19') }}</a></li>
    </ul>
@endcan
