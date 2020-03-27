<div>
    @if (count($tournaments) > 0)
        <div class="row">
            @foreach($tournaments as $nationality => $leagues)
                <div class="col-sm">
                    <span class="flag-icon flag-icon-{{ strtolower($nationality) }}"></span>
                    <ul style="list-style: none; padding: 0">
                        @foreach($leagues as $league)
                            @if ($league->status != 'NOT_DECIDED')
                                <li>
                                    @if ($league->status == 'ACTIVE')
                                        <svg title="{{ __('Ongoing') }}" class="bi bi-clock-history" width="1em"
                                             height="1em" viewBox="0 0 16 16" fill="currentColor"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M8.515 1.019A7 7 0 008 1V0a8 8 0 01.589.022l-.074.997zm2.004.45a7.003 7.003 0 00-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 00-.439-.27l.493-.87a8.025 8.025 0 01.979.654l-.615.789a6.996 6.996 0 00-.418-.302zm1.834 1.79a6.99 6.99 0 00-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 00-.214-.468l.893-.45a7.976 7.976 0 01.45 1.088l-.95.313a7.023 7.023 0 00-.179-.483zm.53 2.507a6.991 6.991 0 00-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 01-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 01-.401.432l-.707-.707z"
                                                  clip-rule="evenodd"/>
                                            <path fill-rule="evenodd"
                                                  d="M8 1a7 7 0 104.95 11.95l.707.707A8.001 8.001 0 118 0v1z"
                                                  clip-rule="evenodd"/>
                                            <path fill-rule="evenodd"
                                                  d="M7.5 3a.5.5 0 01.5.5v5.21l3.248 1.856a.5.5 0 01-.496.868l-3.5-2A.5.5 0 017 9V3.5a.5.5 0 01.5-.5z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    @elseif($league->status == 'NOT_STARTED' || $league->status == 'NOT_DECIDED')
                                        <svg title="{{ __('Not started yet') }}" class="bi bi-info-circle" width="1em"
                                             height="1em" viewBox="0 0 16 16" fill="currentColor"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z"
                                                  clip-rule="evenodd"/>
                                            <path
                                                d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
                                            <circle cx="8" cy="4.5" r="1"/>
                                        </svg>
                                    @else
                                        <svg class="bi bi-check" width="1em" height="1em" viewBox="0 0 16 16"
                                             fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M13.854 3.646a.5.5 0 010 .708l-7 7a.5.5 0 01-.708 0l-3.5-3.5a.5.5 0 11.708-.708L6.5 10.293l6.646-6.647a.5.5 0 01.708 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                    <a href="{{ link_route('show_tournament', ['tournament' => $league->slug]) }}">{{ __($league->name) }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif
</div>
