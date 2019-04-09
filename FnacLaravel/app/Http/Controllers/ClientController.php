<?php
namespace App\Http\Controllers;

use App\Client;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller {
    public function __construct(){
        parent::__construct();
    	$this->middleware('auth');
    }

    public function profile(){
    	$user = Auth::user();
    	return view('profile')->with(compact('user'));
    }

    public function profileValidator($request){
        Validator::make($request->all(), [
            'cli_nom' => 'required',
            'cli_prenom' => 'required',
            'cli_telfixe' => 'required_without_all:cli_telportable',
            'cli_telportable' => 'required_without_all:cli_telfixe',
            'cli_pseudo' => 'required',
            'cli_civilite' => '',
            'cli_motpasse' => 'confirmed',
        ])->validate();
    }

    public function profilePost(Request $request){
    	$this->profileValidator($request);

    	$user=Auth::user();
    	if($request->cli_motpasse){
    		$user->cli_motpasse=bcrypt($request->cli_motpasse);
    	}
    	$user->fill($request->except('cli_mel','cli_motpasse'));
    	$user->save();
    	return redirect()->back()->with('success','Modifications effectuées avec succès');
    }

    public function adminShowAll() {
        $clients = Client::with('magasin', 'commandes')->get();
        return view('admin.clients.all')->with(compact('clients'));
    }
    public function adminShowOne($id) {
        $client = Client::with('magasin', 'commandes', 'adresses', 'avis', 'avisabusifs', 'jeuxvideosfavoris')->findOrFail($id);
        $civilites = Client::getCivilites();
        return view('admin.clients.one')->with(compact('client', 'civilites'));
    }
}
