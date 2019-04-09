@extends('layouts.admin', ['nav' => 'videogames'])

@section('content')

	{!! Form::open(['route'=>'admin::videogame_newPost', 'class' => "panel-container"]) !!}
		<section class="panel panel-75">
			<div class="panel_body">

				{!! Form::hidden('jeu_id',$jeu->jeu_id) !!}

				{!! Form::fieldTitle('jeu_nom', "Nom du jeu", $jeu->jeu_nom) !!}

				{!! Form::fieldText('jeu_description', "Description du jeu", $jeu->jeu_description,['style'=>'resize:none;height:200px'],'textarea') !!}

				{!! Form::fieldDate('jeu_dateparution','Date de parution du jeu',$jeu->jeu_dateparution) !!}

				{!! Form::fieldText('jeu_prixttc', "Prix du jeu", $jeu->jeu_prixttc) !!}

				{!! Form::fieldText('jeu_codebarre', "Code barre du jeu", $jeu->jeu_codebarre) !!}

				{!! Form::fieldText('jeu_stock', "Stock du jeu", $jeu->jeu_stock) !!}

				@foreach($genres as $genre)
					{{$genre->gen_libelle}}<input type="checkbox" name="gen_id[]" value="{{$genre->gen_id}}" {{$genresJeu->where('gen_id',$genre->gen_id)->isNotEmpty()?'checked':''}}><br>
				@endforeach

				{!! Form::fieldSelect('jeu_publiclegal', "Age legal du jeu",$publiclegal, $jeu->jeu_publiclegal,['placeholder'=>'Choisissez un age']) !!}

				{!! Form::fieldSelect('con_id','Console',$consoles,$jeu->con_id,['placeholder' => 'Choisissez une console']) !!}

				{!! Form::fieldSelect('edi_id','Editeur',$editeurs,$jeu->edi_id,['placeholder' => 'Choisissez un editeur']) !!}

				<div class="right">
					{!! Form::submit('Modifier', ['class' => 'btn btn-submit']) !!}
					<a onclick="return confirm('Etes vous sûr de vouloir supprimer ce jeu?')" href="{{route('admin::videogame_delete',$jeu->jeu_id)}}" class="btn btn-alert">Supprimer</a>
				</div>
			</div>
		</section>
	{!! Form::close() !!}

@endsection
