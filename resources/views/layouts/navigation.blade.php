<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/' . app()->getLocale()) }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <i class="material-icons">
                            translate
                        </i> <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @foreach (config('app.available_locales') as $locale => $flag)
                            <?php $params = \Illuminate\Support\Facades\Route::current()->parameters(); $params['locale'] = $locale; ?>
                            
                            <a
                                class="dropdown-item"
                                href="{{ link_route(\Illuminate\Support\Facades\Route::currentRouteName(), $params) }}"
                                @if (app()->getLocale() == $locale) style="font-weight: bold;" @endif
                            >
                                <span class="flag-icon flag-icon-{{ strtolower($flag) }}"></span>

                                {{ config('app.available_locale_names.'.$locale) }}
                            </a>
                        @endforeach
                    </div>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ link_route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ link_route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="https://discord.gg/tzFVp2y"
                           target="_blank">{{ __('Join us on Discord') }}</a>
                    </li>

                    @if (Auth::user()->club)
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->club->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item {{ is_current('show_club', ['club' => Auth::user()->club]) ? 'active' : '' }}"
                                   href="{{ link_route('show_club', ['club' => Auth::user()->club]) }}">{{ __('Clubhouse') }}</a>

                                <a class="dropdown-item {{ is_current('show_club_players', ['club' => Auth::user()->club]) ? 'active' : '' }}"
                                   href="{{ link_route('show_club_players', ['club' => Auth::user()->club]) }}">{{ __('Players') }}</a>

                                <a class="dropdown-item"
                                   href="{{ link_route('quit_job', ['club' => Auth::user()->club]) }}">{{ __('Quit Job') }}</a>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ link_route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ link_route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
