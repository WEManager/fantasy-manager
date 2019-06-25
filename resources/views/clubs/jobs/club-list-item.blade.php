<li>
    <span class="flag-icon flag-icon-{{ strtolower($club->locale) }}"></span>
    @include('clubs.partials.clubname')
    -
    @if ($club->manager)
        {{ __('Manager') }}: {{ optional($club->manager)->name }}
    @elseif (!auth()->check() || auth()->check() && auth()->user()->club == null)
        <a href="{{ route('apply_for_job', ['club' => $club]) }}">{{ __('Apply for job') }}</a>
    @else
        {{ __('No manager') }}
    @endif
</li>
