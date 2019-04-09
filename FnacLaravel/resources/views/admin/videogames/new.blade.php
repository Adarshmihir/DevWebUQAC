@extends('layouts.admin', ['nav' => 'videogames'])

@section('content')
    <h1 class="content_title">
		<span>Ajouter un jeu</span>
	</h1>

	{!! Form::open(['route'=>'admin::videogame_newPost', 'class' => "panel-container"]) !!}
		<section class="panel panel-75">
			<div class="panel_body">
				{!! Form::fieldTitle('jeu_nom', "Nom du jeu", old('jeu_nom')) !!}

				{!! Form::fieldText('jeu_description', "Description du jeu", old('jeu_description')) !!}

				{!! Form::fieldDate('jeu_dateparution','Date de parution du jeu',old('jeu_dateparution')) !!}

				{!! Form::fieldText('jeu_prixttc', "Prix du jeu", old('jeu_prixttc')) !!}

				{!! Form::fieldText('jeu_codebarre', "Code barre du jeu", old('jeu_codebarre')) !!}

				{!! Form::fieldText('jeu_stock', "Stock du jeu", old('jeu_stock')) !!}

				@foreach($genres as $genre)
					{{$genre->gen_libelle}}<input type="checkbox" name="gen_id[]" value="{{$genre->gen_id}}"><br>
				@endforeach

				{!! Form::fieldSelect('jeu_publiclegal', "Age legal du jeu",$publiclegal, old('jeu_publiclegal'),['placeholder'=>'Choisissez un age']) !!}

				{!! Form::fieldSelect('con_id','Console',$consoles,old('con_id'),['placeholder' => 'Choisissez une console']) !!}

				{!! Form::fieldSelect('edi_id','Editeur',$editeurs,old('edi_id'),['placeholder' => 'Choisissez un editeur']) !!}

				<div class="right">
					{!! Form::submit('Créer', ['class' => 'btn btn-submit']) !!}
				</div>
			</div>
		</section>
	{!! Form::close() !!}

@endsection
