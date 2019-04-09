<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @stack('meta')

        <title>FNAC IUT</title>

        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css">
        @stack('styles')
    </head>
    <body>
        <header class="header">
            <div class="header_logo">
                <a href="{{ route('home') }}" class="logo_logo">FNAC</a>
                <span class="logo_baseline">IUT Annecy</span>
            </div>
            <div class="container-fluid header_container">
                <form class="col-md-6 col-sm-6 header_search" action="{{ route('search') }}" method="get">
                    <input type="text" name="q" placeholder="Votre recherche" value="{{ old('q') }}" class="search">
                    {!! Form::select('console',$GameConsoles,old('console')) !!}
                    {!! Form::select('genre',$GameGenres,old('genre')) !!}
                    <button type="submit" class="submit mdi mdi-magnify"></button>
                </form>
                <div class="col-md-2 col-sm-2 header_tab">
                    @if(auth()->check() && auth()->user()->cli_power>=1000)
                        <a href="{{ route('admin::dashboard') }}" class="header_tab_item">
                            <i class="header_tab_item_icon mdi mdi-lock"></i>
                            <strong>Administrateur</strong>
                            <small>Accéder au tableau de bord</small>
                        </a>
                    @endif
                </div>
                <div class="col-md-2 col-sm-2 header_tab">
                    @auth
                        <a href="{{ route('logout') }}" class="header_tab_item">
                            <i class="header_tab_item_icon mdi mdi-account"></i>
                            <strong>{{ $USER->name }}</strong>
                            <small>Se déconnecter</small>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="header_tab_item">
                            <i class="header_tab_item_icon mdi mdi-account"></i>
                            <strong>Mon compte</strong>
                            <small>Se connecter</small>
                        </a>
                    @endauth
                </div>
                <div class="col-md-2 col-sm-2 header_tab">
                    <a href="{{ route('cart_show') }}" class="header_tab_item">
                        <i class="header_tab_item_icon mdi mdi-cart"></i>
                        <strong>Mon panier</strong>
                        <small><span id="cartQuantity">{{ $CART->sum('quantity') }}</span> {{ str_plural('article', $CART->sum('quantity')) }}</small>
                    </a>
                </div>
            </div>
        </header>

        <nav class="sidebar-nav">
            <ul>
                @auth
                        <li><a href="{{ route('profile') }}">Mon profil</a></li>
                @endauth
                <li><a href="{{ route('videogame_all') }}">Tous les jeux</a></li>
                {{-- @foreach --}}
                @foreach($GameConsoles as $id => $console)
                    @if($id == 'all') @continue @endif
                    <li><a href="{{ route('search',['console'=>$id]) }}">{{ $console }}</a></li>
                @endforeach
            </ul>
        </nav>
        <div class="page">
            @yield('content')
        </div>

        <script src="{{ asset('/js/libs/jquery-3.2.1.min.js') }}"></script>
        <script src="https://unpkg.com/popper.js"></script>
        <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
        @stack('scripts')
    </body>
</html>
