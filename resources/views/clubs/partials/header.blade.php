<div class="club--header" style="background-color: {{ $club->club_colors[0] }}">
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1
                    style="
                        color: {{ $club->club_colors[1] }};

                        text-shadow: 1px 1px 0 {{ $club->club_colors[2] }},-1px 1px 0 {{ $club->club_colors[2] }},1px -1px 0 {{ $club->club_colors[2] }},-1px -1px 0 {{ $club->club_colors[2] }},0px 1px 0 {{ $club->club_colors[2] }},0px -1px 0 {{ $club->club_colors[2] }},-1px 0px 0 {{ $club->club_colors[2] }},1px 0px 0 {{ $club->club_colors[2] }},2px 2px 0 {{ $club->club_colors[2] }},-2px 2px 0 {{ $club->club_colors[2] }},2px -2px 0 {{ $club->club_colors[2] }},-2px -2px 0 {{ $club->club_colors[2] }},0px 2px 0 {{ $club->club_colors[2] }},0px -2px 0 {{ $club->club_colors[2] }},-2px 0px 0 {{ $club->club_colors[2] }},2px 0px 0 {{ $club->club_colors[2] }},1px 2px 0 {{ $club->club_colors[2] }},-1px 2px 0 {{ $club->club_colors[2] }},1px -2px 0 {{ $club->club_colors[2] }},-1px -2px 0 {{ $club->club_colors[2] }},2px 1px 0 {{ $club->club_colors[2] }},-2px 1px 0 {{ $club->club_colors[2] }},2px -1px 0 {{ $club->club_colors[2] }},-2px -1px 0 {{ $club->club_colors[2] }};
                        ">{{ $club->name }} ({{ $club->locale }})</h1>

                @if ($club->manager)
                    <small style="color: {{ $club->club_colors[1] }};text-shadow: 1px 1px 0 {{ $club->club_colors[2] }},-1px 1px 0 {{ $club->club_colors[2] }};">{{ __('Manager') }}: {{ optional($club->manager)->name }}</small>
                @else
                    <small style="color: {{ $club->club_colors[1] }};">{{ __('No manager') }}.</small>
                @endif
            </div>
        </div>
    </div>
</div>
