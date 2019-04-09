<div class="jumbotron jumbotron-fluid">
	<div class="container">
		<a type="button" href="{{route('comment_signal',$comment->avi_id)}}" class="btn btn-light">Signaler !</a><br>
		{{$comment->avi_titre}}
		@include('partials.stars', ['rating' => $comment->avi_note])
		{{$comment->client->cli_nom}} {{$comment->client->cli_prenom}}<br>
		{{$comment->avi_date->toDateString()}}<br>
		{{$comment->avi_detail}}<br>
		<button type="button" class="btn btn-success" onclick="thumb('up',{{$comment->avi_id}})"><i class="mdi mdi-thumb-up" aria-hidden="true"></i> <span id="{{$comment->avi_id}}up">{{$comment->avi_nbutileoui}}</span></button>
		<button type="button" class="btn btn-danger" onclick="thumb('down',{{$comment->avi_id}})"><i class="mdi mdi-thumb-down" aria-hidden="true"></i> <span id="{{$comment->avi_id}}down">{{$comment->avi_nbutilenon}}</span></button>
	</div>
</div>