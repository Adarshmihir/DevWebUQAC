<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(){
    	parent::__construct();
    	$this->middleware('auth');
    	$this->middleware('cartNotEmpty');
    }

    public function home(){
    	$adresses=\App\Adresse::where('cli_id',Auth::id())->where('adr_type','Livraison')->get();
    	$relais= \App\Relais::all();
    	$magasins=\App\Magasin::all();
    	return view('order.home')->with(compact('adresses','relais','magasins'));
    }
}
