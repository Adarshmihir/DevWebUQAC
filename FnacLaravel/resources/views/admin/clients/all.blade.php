@extends('layouts.admin', ['nav' => 'clients'])

@section('content')
    <h1 class="content_title">
		<span>Tous les clients</span>
		<div class="content_tools"></div>
	</h1>
    <table class="table">
		<thead>
		<tr class="table_head">
			<th width="100">#</th>
			<th>Nom</th>
			<th>Pseudo</th>
            <th>Adresse mail</th>
            <th>Téléphone</th>
            <th>Magasin</th>
            <th>Nb commandes</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
            @forelse($clients as $client)
                <tr>
                    <td><a href="{{ route('admin::clients_one', $client->cli_id) }}" class="clickableRow">#{{ $client->cli_id }}</a></td>
                    <td>{{ $client->cli_civilite }} {{ $client->name }}</td>
                    <td>{{ $client->cli_pseudo }}</td>
                    <td>{{ $client->cli_mel }}</td>
                    <td><i class="mdi mdi-phone"></i> {{ $client->cli_telfixe }} - {{ $client->cli_telportable }}</td>
                    <td>{{ $client->magasin ? $client->magasin->mag_ville : '-' }}</td>
                    <td>{{ $client->commandes->count() }} commande(s)</td>
                    <td class="right">
                        @if($client->cli_power >= 1000)
                            <span class="badge badge-m-teal">Administrateur</span>
                        @else
                            <span class="badge badge-info">Client</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center">
                        <i>Aucun client</i>
                    </td>
                </tr>
            @endforelse
		</tbody>
	</table>

@endsection
