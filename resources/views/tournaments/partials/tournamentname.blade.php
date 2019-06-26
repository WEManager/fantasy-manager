<a href="{{ link_route('show_tournament', ['tournament' => $group->tournament_id]) }}#{{ slugify($group->name) }}">
    {{ $group->tournament->name }}
    @if ($group->name != $group->tournament->name) <br><small>{{ $group->name }}</small> @endif
</a>