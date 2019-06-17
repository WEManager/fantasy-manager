<h3>Squads</h3>
<ul>
    <li><a href="{{ route('show_club', ['club' => $club]) }}">{{ __('Club') }}</a></li>
    <li><a href="{{ route('show_club', ['club' => $club]) }}/senior">Senior</a></li>
    <li><a href="{{ route('show_club', ['club' => $club]) }}/u21">Reserv / U21</a></li>
    <li><a href="{{ route('show_club', ['club' => $club]) }}/u19">U19</a></li>
</ul>
@can('update', $club)
    <h3>Lineups</h3>
    <ul>
        <li><a href="{{ route('edit_lineup', ['club' => $club, 'squad' => 'senior']) }}">Senior lineup</a></li>
        <li><a href="{{ route('edit_lineup', ['club' => $club, 'squad' => 'u21']) }}">Reserve lineup</a></li>
        <li><a href="{{ route('edit_lineup', ['club' => $club, 'squad' => 'u19']) }}">U19 lineup</a></li>
    </ul>
@endcan
