@extends('layouts.admin', ['nav' => 'reviews'])

@section('content')
    <h1 class="content_title">
		<span>{{ $title }}</span>
		<div class="content_tools"></div>
	</h1>
    <table class="table">
		<thead>
		<tr class="table_head">
			<th width="100">#</th>
			<th>Jeu</th>
			<th>Client</th>
            <th>Date</th>
            <th>Note</th>
            <th>Signalements</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
            @forelse($reviews as $review)
                <tr>
                    <td><a href="{{ route('admin::reviews_one', $review->avi_id) }}" class="clickableRow">{{ $review->avi_id }}</a></td>
                    <td>#{{ $review->jeuvideo->jeu_id }} - {{ str_limit($review->jeuvideo->jeu_nom, 25) }}</td>
                    <td>#{{ $review->client->cli_id }} - {{ $review->client->name }}</td>
                    <td>{{ $review->avi_date->format('d/m/Y') }}</td>
                    <td><i class="mdi mdi-star"></i> {{ $review->avi_note }}</td>
                    <td>
                        @if($review->clientsabusif->count() > 0)
                            {{ $review->clientsabusif->count() }} signalement(s)
                        @else
                            Pas de signalement
                        @endif
                    </td>
                    <td class="right">
                        @if($review->clientsabusif->count() > 0)
                            <span class="badge badge-warning">En attente de modération</span>
                        @elseif($review->avi_nbutileoui < $review->avi_nbutilenon)
                            <span class="badge badge-system">Publié</span>
                        @else
                            <span class="badge badge-success">Publié</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">
                        <i>Aucun jeu</i>
                    </td>
                </tr>
            @endforelse
		</tbody>
	</table>

@endsection
