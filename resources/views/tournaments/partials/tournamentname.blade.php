<a href="{{ route('show_tournament', [$group->tournament_id]) }}#{{ slugify($group->name) }}">
    {{ $group->tournament->name }}
    @if ($group->name != $group->tournament->name) <br><small>{{ $group->name }}</small> @endif
</a>