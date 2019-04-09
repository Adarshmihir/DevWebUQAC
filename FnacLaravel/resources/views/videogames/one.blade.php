@extends('layouts.default')

@section('content')
<div class="container-fluid">
    @if(session()->has('success'))
        <div class="col-sm-12">
            <div class="alert alert-success" role="alert">
                {{session()->get('success')}}
            </div>
        </div>
    @endif
	<div class="col-sm-12"><h1>{{$jeu->jeu_nom}}</h1></div>
	<div class="col-sm-6">
		@include('partials.carousel', ['photos' => $jeu->photos])
	</div>
	<div class="col-sm-6">
		{{$jeu->jeu_description}}<br>
		{{$jeu->jeu_dateparution->format('M  Y')}}<br>
		{{$jeu->jeu_prixttc}} €<br>
		{{$jeu->jeu_publiclegal}}<br>
		{{$jeu->jeu_stock}} en Stock<br>
		<div class="col-sm-4 col-md-3 col-lg-2">
		{!! Form::number('quantity',1,['id' => 'quantity','class'=>'form-control']) !!}
		</div>
    	<button id="AddToCart" class="btn btn-primary col-sm-4">Ajouter au panier</button>
        @auth
    	<button class="btn {{$favoris>0?'red':''}}" id="favorite"><i class="mdi mdi-heart col-sm-4" aria-hidden="true"></i></button>
        @endauth
    	<p id="CurrentPanier"></p>

        <div class="col-sm-12"></div>

       @each('partials.comment',$comments,'comment')

	</div>
</div>
@endsection

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#AddToCart').click(function(){
				$.ajax({
                    method: "POST",
                    url: "{{ route('cart_ajax_add') }}",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "id": {{$jeu->jeu_id}},
                        "quantity": $('#quantity').val(),
                    },
                    success: function (res) {
                    	$('#CurrentPanier').text('Ajout effectué avec succès');
                        $('#cartQuantity').text(res);
                    },
                    error: function () {
                    	$('#CurrentPanier').text('Erreur lors de la modification de votre panier');
                    }
                });
			});

			$('#favorite').click(function(){
				$.ajax({
                    method: "POST",
                    url: "{{ route('favoriteAdd') }}",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "jeu_id": {{$jeu->jeu_id}},
                    },
                    success: function (res) {
                    	$('#favorite').toggleClass('red');
                    },
                    error: function () {
                    }
                });
			});
		});

        function thumb(method,id){
                $.ajax({
                    method: "POST",
                    url: "{{ route('changeThumb') }}",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "avi_id": id,
                        "method": method,
                    },
                    success: function (res) {
                        $('#'+id+method).text(parseInt($('#'+id+method).text())+1);
                    },
                    error: function () {
                    }
                });
        }
	</script>
@endpush
