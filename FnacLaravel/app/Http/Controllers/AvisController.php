<?php

namespace App\Http\Controllers;

use App\Avis;
use App\AvisAbusif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AvisController extends Controller
{
	public function __construct(){
		parent::__construct();
		$this->middleware('auth');
	}

    public function signal($id){
        $AvisAbusif = new AvisAbusif();
        $AvisAbusif->cli_id = Auth::id();
        $AvisAbusif->avi_id = $id;
        $AvisAbusif->save();
    	// Commentaire déjà report -> violer une contrainte primary -> save refait
    	return redirect()->back()->with('success','Commentaire signalé avec succès');
    }

    public function thumb(Request $request){
    	$avis=\App\Avis::find($request->avi_id);
    	if($request->method=='up'){
    		$avis->avi_nbutileoui+=1;
    	}else{
    		$avis->avi_nbutilenon+=1;
    	}
    	$avis->save();
    }

	public function adminShowAll() {
		$reviews = Avis::with('jeuvideo', 'client', 'clientsabusif')->get();
		return view('admin.reviews.all')->with(compact('reviews') + ['title' => 'Tous les avis']);
	}

	public function adminShowWaiting() {
		$abusifs = AvisAbusif::select('avi_id')->get();
		$reviews = Avis::with('jeuvideo', 'client', 'clientsabusif')->whereIn('avi_id', $abusifs->pluck('avi_id'))->get();
		return view('admin.reviews.all')->with(compact('reviews') + ['title' => 'Avis à modérer']);
	}

	public function adminShowOne($id) {
		$review = Avis::where('avi_id', $id)->with('jeuvideo', 'client', 'clientsabusif')->firstOrFail();
		return view('admin.reviews.one')->with(compact('review'));
	}
	public function adminDeleteAvis($id) {
		$review = Avis::where('avi_id', $id)->firstOrFail();
		AvisAbusif::where('avi_id', $id)->delete();
		$review->delete();
		Session::flash('flash_message', ['type' => "success", 'content' => "Avis #{$id} supprimé !"]);
		return redirect()->route('admin::reviews_all');
	}
	public function adminDeleteAbusif($id, $cid) {
		$review = Avis::where('avi_id', $id)->firstOrFail();
		AvisAbusif::where('avi_id', $id)->where('cli_id', $cid)->delete();
		Session::flash('flash_message', ['type' => "success", 'content' => "Signalement de l'avis #{$id} supprimé !"]);
		return redirect()->route('admin::reviews_one', $id);
	}
}
