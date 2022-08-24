<a href="{{ link_route('club.show', ['club' => $club]) }}">{{ $club->name }}</a>
<div class="club_colors" style="background-color: {{ $club->colors[0] }}; border-color: {{ $club->colors[1] }}"></div>
