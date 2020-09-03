<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>


        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        @yield('styles')
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @livewireStyles

	</head>
	<body>
		<div id="app">
			<header>
				<nav class="navbar navbar-expand-sm navbar-dark">
					<!-- Brand -->
					<div class="container">
						<a class="navbar-brand" id="logo" href="{{ url('/') }}">
							<h1 class="display-4">
								<span style="color: #439daa;"  >H</span><span style="color: #5385a0;" >S</span><small>movies</small>
							</h1>

						</a>
						<!-- left nav -->
						<ul class="navbar-nav mr-auto">

						</ul>
						<!-- Toggler/collapsibe Button -->
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
							<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse" id="collapsibleNavbar">
						<!-- Links -->
							<ul class="navbar-nav ml-auto">
								<li class="nav-item">
									<a href="{{ Route('actors.index') }}"  class="nav-link links">
										Actors
									</a>
                                </li>
                                @guest
                                    <li class="nav-item">
                                        <a class="nav-link links" href="{{ route('login') }}">Login</a>
                                    </li>
                                    @if (Route::has('register'))
                                        <li class="nav-item">
                                            <a class="nav-link links" href="{{ route('register') }}">Register</a>
                                        </li>
                                    @endif
                                @else
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }} <span class="caret"></span>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            @can('see_dashboard')
                                            <a class="dropdown-item" href="/dashboard/home">
                                                dashboard
                                            </a>
                                            @endcan
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>

                                @endguest
							</ul>
						</div>
					</div>
				</nav>
				<div class="row justify-content-center ">
                    @livewire('search-movies')
				</div>

			</header>

			<section id="section1">
				@yield('content')
			</section>
		</div>
    <script>

            window.auth = @json([
              'userId' => auth()->id(),
              'roles' => auth()->user() ? auth()->user()->roles()->pluck('name')->unique() : [],
            'abilities' => auth()->user() ? auth()->user()->abilities() : []
            ])
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    @livewireScripts
    @yield('scripts')
	</body>
</html>

