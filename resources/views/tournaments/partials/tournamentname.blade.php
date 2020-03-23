<a href="{{ link_route('show_tournament', ['tournament' => $group->tournament]) }}#{{ slugify($group->name) }}">
    <span class="flag-icon flag-icon-{{ strtolower($group->tournament->nationality) }}"></span>
    {{ $group->tournament->name }}
    @if ($group->name != $group->tournament->name) <br><small>{{ $group->name }}</small> @endif
</a>
