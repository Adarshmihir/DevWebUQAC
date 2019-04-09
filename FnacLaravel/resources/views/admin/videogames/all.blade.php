@extends('layouts.admin', ['nav' => 'videogames'])

@section('content')
    <h1 class="content_title">
		<span>Tous les jeux</span>
		<div class="content_tools">
			<a href="{{ route('admin::videogame_new') }}" class="content_item mdi mdi-plus"></a>
		</div>
	</h1>
    <table class="table">
		<thead>
		<tr class="table_head">
			<th width="100">#</th>
			<th>Titre</th>
			<th>Console</th>
            <th>Genre</th>
			<th width="150"></th>
		</tr>
		</thead>
		<tbody>
            @forelse($games as $game)
                <tr>
                    <td>{{ $game->jeu_id }}</td>
                    <td><a href="{{route('admin::videogame_one',$game->jeu_id)}}" class="clickableRow">{{ $game->jeu_nom }}</a></td>
                    <td>{{ $game->consoles->con_nom }}</td>
                    <td>@foreach($game->genres as $genre){{$loop->first?'':','}} {{$genre->gen_libelle}}@endforeach</td>
                    <td class="right"><span class="badge badge-success">Mis en avant</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">Aucun jeu</td>
                </tr>
            @endforelse
		</tbody>
	</table>

@endsection
