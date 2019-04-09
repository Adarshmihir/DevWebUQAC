@extends('layouts.default')

@section('content')
    <h1>Accueil</h1>

    @include('partials.productslist', ['games' => $jeux])
@endsection
