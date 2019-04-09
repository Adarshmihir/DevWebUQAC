@extends('layouts.default')

@section('content')
	<h1>Adresses :</h1>
	@forelse($adresses as $adresse)
		<div class="dropdown-divider"></div>
		{{$adresse->adr_nom}}<br>
		{{$adresse->adr_rue}}<br>
		{{$adresse->adr_cp}}<br>
		{{$adresse->adr_ville}}<br>
	@empty
		<h3>Pas d'adresse ...</h3>
	@endforelse
	<div class="dropdown-divider"></div>
	<h1>Magasins :</h1>
	@forelse($magasins as $magasin)
		<div class="dropdown-divider"></div>
		{{$magasin->mag_nom}}<br>
		{{$magasin->mag_ville}}<br>
	@empty
		<h3>Pas de magasins ...</h3>
	@endforelse
	<div class="dropdown-divider"></div>
	<h1>Adresses :</h1>
	@forelse($relais as $relai)
		<div class="dropdown-divider"></div>
		{{$relai->rel_nom}}<br>
		{{$relai->rel_rue}}<br>
		{{$relai->rel_cp}}<br>
		{{$relai->rel_ville}}<br>
	@empty
		<h3>Pas de relais ...</h3>
	@endforelse
@endsection
