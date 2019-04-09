@extends('layouts.default')

@section('content')
  @if($carts->isNotEmpty())
    <a href="{{route('order_home')}}" class="btn btn-primary">Commander !</a>
  @endif
	<div class="col-lg-12" id="cartAll">
		@forelse($carts as $cart)
			<div class="col-sm-12" id="{{$cart['id']}}">
				<img src="@if($cart['pho_url']) {{asset('img/'.$cart['pho_url'])}} @else {{asset('img/empty.png')}} @endif">
				<button onclick="changeItem({{ $cart['id'] }},'+')"><i class="mdi mdi-plus" aria-hidden="true"></i></button>
				<button onclick="changeItem({{ $cart['id'] }},'-')"><i class="mdi mdi-minus" aria-hidden="true"></i></button>
				{!! Form::number('number',$cart['quantity'],['disabled'=>'disabled','id'=>'number']) !!}
				<button onclick="changeItem({{$cart['id']}})">Supprimer</button>
			</div>
		@empty
			<h2>Votre panier est vide ...</h2>
		@endforelse
	</div>
@endsection

@push('scripts')
<script type="text/javascript">
	$(document).ready(function(){

	});

	function changeItem(id,method=null){
		$.ajax({
            method: "POST",
            url: method==null?"{{ route('cart_ajax_delete') }}":"{{route('cart_ajax_add')}}",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
       	    data: {
       	    	"method":method,
       	    	"quantity":1,
                "id": id,
            },
            success: function (res) {
            	if(method==null)
              		$('#'+id).remove();
              	else if(method=='+')
              		$('#number').val(parseInt($('#number').val())+1);
              	else
              		$('#number').val(parseInt($('#number').val())-1);
              	$('#cartQuantity').text(res);
              	if(res==0)
              		$('#cartAll').append($('<h2/>',{text:'Votre panier est vide ...'}));
            },
            error: function () {
            	
            }
        });
	}
</script>
@endpush