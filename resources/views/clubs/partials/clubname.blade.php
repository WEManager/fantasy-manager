<a href="{{ link_route('show_club', ['club' => $club]) }}">{{ $club->name }}</a>
<div class="club_colors" style="background-color: {{ $club->colors[0] }}; border-color: {{ $club->colors[1] }}"></div>
