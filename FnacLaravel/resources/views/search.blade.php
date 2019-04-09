@extends('layouts.default')

@section('content')
    <h1>Résultats de la recherche</h1>

    @include('partials.productslist', ['games' => $games])
@endsection
