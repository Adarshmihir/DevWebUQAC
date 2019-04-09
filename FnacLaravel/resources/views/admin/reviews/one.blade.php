@extends('layouts.admin', ['nav' => 'reviews'])

@section('content')
    <h1 class="content_title">
		<div class="content_before">
			<a href="{{ route('admin::reviews_all') }}" class="content_item mdi mdi-chevron-left"></a>
		</div>
		<span>Avis &gt; Consultation (#{{ $review->avi_id }})</span>
	</h1>

	<div class="panel-container">
		<div class="panel-75">
            <section class="panel">
                <div class="panel_body">
                    <table class="display-table">
                        <tr>
                            <th>ID Avis</th>
                            <td>#{{ $review->avi_id }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $review->avi_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jeu</th>
                            <td><a href="{{ route('admin::videogame_one', $review->jeuvideo->jeu_id) }}">#{{ $review->jeuvideo->jeu_id }} - {{ $review->jeuvideo->jeu_nom }}</a></td>
                        </tr>
                        <tr>
                            <th>Client</th>
                            <td><a href="{{ route('admin::videogame_one', $review->jeuvideo->jeu_id) }}">#{{ $review->client->cli_id }} - {{ $review->client->name }}</a></td>
                        </tr>
                        <tr>
                            <th>Note</th>
                            <td><i class="mdi mdi-star"></i> {{ $review->avi_note }}</td>
                        </tr>
                        <tr>
                            <th>Titre</th>
                            <td>{{ $review->avi_titre }}</td>
                        </tr>
                        <tr>
                            <th>Détail</th>
                            <td>{{ $review->avi_detail }}</td>
                        </tr>
                        <tr>
                            <th>Utile</th>
                            <td>
                                {{ $review->avi_nbutileoui }} ({{ number_format($review->avi_nbutileoui / ($review->avi_nbutileoui + $review->avi_nbutilenon) * 100, 0) }} %) / {{ $review->avi_nbutilenon }} ({{ number_format($review->avi_nbutilenon / ($review->avi_nbutileoui + $review->avi_nbutilenon) * 100, 0) }} %)
                            </td>
                        </tr>
                    </table>
                </div>
            </section>
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
                            @forelse($review->clientsabusif as $abusif)
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
        {!! Form::open(['route' => ['admin::reviews_delete', $review->avi_id], 'class' => "panel-25", 'method' => 'DELETE']) !!}
            {!! Form::submit("SUPPRIMER L'AVIS", ['class' => 'btn btn-alert mdi mdi-delete', 'style' => 'width: 100%;']) !!}
    	{!! Form::close() !!}
    </div>

@endsection
