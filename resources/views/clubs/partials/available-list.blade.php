<h1>{{ __('List of available clubs') }}</h1>
<div class="row">
    <div class="col-xs-4">

        <ul>
            @foreach($clubs->take(30) as $club)
                @include('clubs.jobs.club-list-item')
            @endforeach
        </ul>

    </div>
    <div class="col-xs-4">

        <ul>
            @foreach($clubs->slice(30)->take(30) as $club)
                @include('clubs.jobs.club-list-item')
            @endforeach
        </ul>

    </div>
    <div class="col-xs-4">

        <ul>
            @foreach($clubs->slice(60)->take(30) as $club)
                @include('clubs.jobs.club-list-item')
            @endforeach
        </ul>

    </div>
</div>
