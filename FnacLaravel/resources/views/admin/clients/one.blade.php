@extends('layouts.admin', ['nav' => 'clients'])

@section('content')
    <h1 class="content_title">
		<div class="content_before">
			<a href="{{ route('admin::clients_all') }}" class="content_item mdi mdi-chevron-left"></a>
		</div>
		<span>Clients &gt; Consultation (#{{ $client->cli_id }} - {{ $client->name }})</span>
	</h1>

    <div class="panel-container">
		<div class="panel-75">
            {!! Form::open(['route' => ['admin::clients_one', $client->cli_id], 'class' => "panel"]) !!}
                <div class="panel_body">
                    <table class="display-table">
                        <tr>
                            <th>ID Client</th>
                            <td>#{{ $client->cli_id }}</td>
                        </tr>
                        <tr>
                            <th>Pseudo</th>
                            <td>{{ $client->cli_pseudo }}</td>
                        </tr>
                        <tr>
                            <th>Civilité</th>
                            <td>{!! Form::fieldSelectMin('cli_civilite', 'Civilité', $civilites, old('cli_civilite', $client->cli_civilite)) !!}</td>
                        </tr>
                        <tr>
                            <th>Nom</th>
                            <td>{!! Form::fieldTextMin('cli_nom', 'Nom', old('cli_nom', $client->cli_nom)) !!}</td>
                        </tr>
                        <tr>
                            <th>Prénom</th>
                            <td>{!! Form::fieldTextMin('cli_prenom', 'Prénom', old('cli_prenom', $client->cli_prenom)) !!}</td>
                        </tr>
                        <tr>
                            <th>Mail</th>
                            <td>{!! Form::fieldTextMin('cli_mel', 'Adresse mail', old('cli_mel', $client->cli_mel), [], 'email') !!}</td>
                        </tr>

                    </table>
                </div>
            {!! Form::close() !!}
            <section class="panel">
                <div class="panel_body">
                    <h2 class="panel_title">Signalements</h2>

                    <table class="left">
                		<thead>
                		<tr class="table_head">
                			<th>Client</th>
                            <th>Mail</th>
                			<th>&nbsp;</th>
                		</tr>
                		</thead>
                		<tbody>
                            @forelse([] as $abusif)
                                <tr>
                                    <td>#{{ $abusif->cli_id }} - {{ $abusif->name }}</td>
                                    <td>{{ $abusif->cli_mel }}</td>
                                    <td class="right">
                                        {!! Form::open(['route' => ['admin::reviews_delete-abusif', 'id' => $review->avi_id, 'cid' => $abusif->cli_id], 'method' => 'DELETE']) !!}
                                            {!! Form::submit("Supprimer signalement", ['class' => 'btn btn-small btn-flat btn-alert mdi mdi-delete']) !!}
                                    	{!! Form::close() !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="center">
                                        <i>Aucun signalement pour cet avis</i>
                                    </td>
                                </tr>
                            @endforelse
                		</tbody>
                	</table>
                </div>
            </section>
		</div>

    </div>

@endsection
