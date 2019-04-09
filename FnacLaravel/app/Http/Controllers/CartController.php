<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Photo;

class CartController extends Controller
{
	public function addToCart(Request $request){
		//Session::forget('cart');
		$cart = collect(Session::get('cart') ?: []);
		if($cart->where('id', $request->id)->count() > 0) {
			$cart = $cart->transform(function ($item, $key) use ($request) {
				if($item['id'] == $request->id){
					if($request->has('method') && $request->method=='-'){
						$item['quantity'] -= $request->quantity;
					}else{
						$item['quantity'] += $request->quantity;
					}
				}
				return $item;
			});
		} else {
			$cart->push(['id' => $request->id, 'quantity' => $request->quantity]);
		}
		Session::put('cart', $cart->all());
		return response()->json($cart->sum('quantity'));
	}

	public function show(){
		$carts=collect();
		foreach (Session::get('cart')?:[] as $cart) {
			$photo=Photo::where('jeu_id',$cart['id'])->first();
			$carts->push(['pho_url' => $photo?$photo->pho_url:null,'quantity' => $cart['quantity'],'id'=>$cart['id']]);
		}
		return view('cart.cart')->with(compact('carts'));
	}

	public function delete(Request $request){
		$cart = collect(Session::get('cart') ?: []);
		$cart=$cart->reject(function($value,$key) use ($request){
			return $value['id']==$request->id;
		});
		Session::put('cart',$cart->all());
		return response()->json($cart->sum('quantity'));
	}
}
