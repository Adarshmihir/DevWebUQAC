<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#3F51B5">
        @stack('meta')

        <title>FNAC IUT - Administrateur</title>

        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
        <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css">
        @stack('styles')
    </head>
	<body>
		<header class="navbar">
			<i class="navbar_toggle mdi mdi-menu" id="NavbarToggle"></i>
			<div class="navbar_logo">
				<img src="{{ asset('img/logo_white.png') }}" alt="FNAC">
			</div>
			<div class="navbar_title">Administration</div>
			<div class="navbar_tool" id="NavbarTool">
				<li><span style="font-size:16px;padding:0 10px;">{{ date('d/m/Y') }}</span></li>
				<li class="tooltip" data-tooltip="Déconnexion"><a href="{{ route('logout') }}"><i class="mdi mdi-power"></i></a></li>
			</div>
		</header>
		<aside class="sidebar">
			<div class="sidebar_profile" style="background-image: url('{{ asset('img/fnac1.jpg') }}');">
				<div class="sidebar_profile-meta">
					<span class="sidebar_profile-name">{{ $USER->name }}</span>
					Service @TODO
				</div>
			</div>

			<ul class="nav" id="SidebarNav">
				<li class="nav_item{{ $nav=='dashboard'?' is-current is-active':'' }}">
					<a href="{{ route('admin::dashboard') }}">
						<i class="nav_item-icon mdi mdi-chart-areaspline"></i>
						<span class="nav_item-title">Tableau de bord</span>
					</a>
				</li>
				<li class="nav_item{{ $nav=='videogame'?' is-current is-active':'' }}">
					<a href="#nav">
						<span class="nav_item-right"><i class="mdi mdi-menu-down"></i></span>
						<i class="nav_item-icon mdi mdi-gamepad-variant"></i>
						<span class="nav_item-title">Jeux</span>
					</a>
					<ul class="nav_item_sub">
						<li><a href="{{ route('admin::videogame_all') }}">Tous les jeux</a></li>
						<li><a href="{{ route('admin::videogame_new') }}">Nouveau jeu</a></li>
					</ul>
				</li>
				<li class="nav_item{{ $nav=='reviews'?' is-current is-active':'' }}">
					<a href="#nav">
						<span class="nav_item-right"><i class="mdi mdi-menu-down"></i></span>
						<i class="nav_item-icon mdi mdi-message-bulleted"></i>
						<span class="nav_item-title">Avis</span>
					</a>
					<ul class="nav_item_sub">
						<li><a href="{{ route('admin::reviews_all') }}">Tous les avis</a></li>
						<li><a href="{{ route('admin::reviews_waiting') }}">En attente de modération</a></li>
					</ul>
				</li>
				<li class="nav_item{{ $nav=='clients'?' is-current is-active':'' }}">
					<a href="#nav">
                        <span class="nav_item-right"><i class="mdi mdi-menu-down"></i></span>
						<i class="nav_item-icon mdi mdi-account"></i>
						<span class="nav_item-title">Clients</span>
					</a>
                    <ul class="nav_item_sub">
						<li><a href="{{ route('admin::clients_all') }}">Tous les clients</a></li>
						<li><a href="{{ route('admin::clients_new') }}">Nouvel utilisateur</a></li>
					</ul>
				</li>
				<li class="nav_item">
					<a href="#">
						<i class="nav_item-icon mdi mdi-settings"></i>
						<span class="nav_item-title">Options</span>
					</a>
				</li>
			</ul>
		</aside>

        <div class="toast-container" id="ToastContainer">
        	@if(Session::has('flash_message'))
        		<div class="toast">
        			<span class="toast_badge{{ session('flash_message.type') ? " toast_badge-".session('flash_message.type') : "" }}"></span>
        			{{ session('flash_message.content') }}
        		</div>
        	@endif
        </div>
		<section class="content">
            @yield('content')
		</section>

        <script src="{{ asset('/js/libs/jquery-3.2.1.min.js') }}"></script>
        <script src="https://fcpe73.ovh/js/libs/lodash.min.js"></script>
		<script src="https://fcpe73.ovh/js/libs/modernizr-admin.js"></script>
		<script src="https://fcpe73.ovh/js/admin.js"></script>
		<script src="https://fcpe73.ovh/js/inputs.js"></script>
        @stack('scripts')
    </body>
</html>
