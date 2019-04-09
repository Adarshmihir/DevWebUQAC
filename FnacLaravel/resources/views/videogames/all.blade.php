@extends('layouts.default')

@section('content')
    <h1>Tous les jeux</h1>

    @include('partials.productslist', ['games' => $jeuxvideo])
@endsection
