<li>
    {{ $club->locale }}
    <a href="{{ route('show_club', ['club' => $club]) }}">{{ $club->name }}</a>
    -
    @if ($club->manager)
        {{ __('Manager') }}: {{ optional($club->manager)->name }}
    @elseif (auth()->check() && auth()->user()->club == null)
        <a href="{{ route('apply_for_job', ['club' => $club]) }}">{{ __('Apply for job') }}</a>
    @else
        {{ __('No manager') }}
    @endif
</li>