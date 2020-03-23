<div>
    @if (count($tournaments) > 0)
        <div class="row">
            @foreach($tournaments as $nationality => $leagues)
                <div class="col-sm">
                    <span class="flag-icon flag-icon-{{ strtolower($nationality) }}"></span>
                    <ul style="list-style: none; padding: 0">
                        @foreach($leagues as $league)
                            <li>
                                <a href="{{ link_route('show_tournament', ['tournament' => $league->slug]) }}">{{ __($league->name) }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif
</div>
