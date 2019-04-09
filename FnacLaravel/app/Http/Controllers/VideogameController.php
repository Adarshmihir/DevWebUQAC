<?php

namespace App\Http\Controllers;

use App\Jeuvideo;
use App\Avis;
use App\AvisAbusif;
use App\Favoris;
use Auth;
use App\Console;
use App\Genre;
use App\Editeur;
use Illuminate\Http\Request;

class VideogameController extends Controller {

	public function showHome() {
		$jeux = Jeuvideo::with('photos', 'avis')->take(4)->get();
	    return view('videogames.home', compact('jeux'));
	}

    public function showAll() {
    	$jeuxvideo=Jeuvideo::with('photos', 'avis')->get();
    	return view("videogames.all")->with(compact('jeuxvideo'));
    }

    public function showOne($id,$slug){
    	$jeu = JeuVideo::findOrFail($id);

    	if (!($slug == str_slug($jeu->jeu_nom))) return redirect()->route('videogame_one', array("id"=>$id,"slug"=>str_slug($jeu->jeu_nom)));

        $comments = Avis::where('jeu_id',$id)->with('client')->orderBy('avi_id','DESC')->get();
        $abusifs = AvisAbusif::whereIn('avi_id', $comments->pluck('avi_id')->all())->get();

        $platforms = $this->getPlatforms();
        $genres    = $this->getGenres();
        $editors   = $this->getEditors();
        $favoris = \App\Favoris::where('cli_id',Auth::id())->where('jeu_id',$id)->count();

    	return view('videogames.one')->with(compact('genres', 'editors', 'platforms','jeu','comments', 'abusifs','favoris'));
	}

    public function getPlatforms() {
        return Console::all();
    }

    public function getGenres() {
        return Genre::all();
    }

    public function getEditors() {
        return Editeur::all();
    }

	public function adminShowAll() {
		$games = Jeuvideo::with('genres','photos', 'avis', 'consoles')->get();
		return view("admin.videogames.all")->with(compact('games'));
	}

    public function adminShowNew(){
        $consoles= \App\Console::all();
        $consoles=$consoles->pluck('con_nom','con_id')->all();
        $genres= \App\Genre::all();
        $editeurs= \App\Editeur::all();
        $editeurs=$editeurs->pluck('edi_nom','edi_id')->all();
        $publiclegal=['18+'=>'18+','16+'=>'16+','12+'=>'12+','7+'=>'7+','3+'=>'3+'];
        return view('admin.videogames.new')->with(compact('consoles','editeurs','publiclegal','genres'));
    }

    public function adminShowNewPost(Request $request){
        //Pas de validator car compétence acquise pour gain de temps
        if($request->jeu_id)
            $jeu=\App\Jeuvideo::find($request->jeu_id);
        else
            $jeu=new \App\Jeuvideo();
        $jeu->fill($request->except('_token','gen_id','jeu_id'));
        $jeu->save();
        \App\GenreJeu::where('jeu_id',$request->jeu_id)->delete();
        if($request->gen_id)
            foreach($request->gen_id as $gen_id){
                $gen=new \App\GenreJeu();
                $gen->gen_id=$gen_id;
                $gen->jeu_id=$jeu->jeu_id;
                $gen->save();
            }
        return redirect()->route('admin::videogame_one',$jeu->jeu_id);
    }

    public function adminShowOne($id){
        $consoles= \App\Console::all();
        $consoles=$consoles->pluck('con_nom','con_id')->all();
        $genres= \App\Genre::all();
        $editeurs= \App\Editeur::all();
        $editeurs=$editeurs->pluck('edi_nom','edi_id')->all();
        $genresJeu=\App\GenreJeu::where('jeu_id',$id)->get();
        $publiclegal=['18+'=>'18+','16+'=>'16+','12+'=>'12+','7+'=>'7+','3+'=>'3+'];
        $jeu=\App\Jeuvideo::find($id);
        return view('admin/videogames/one')->with(compact('jeu','consoles','genres','editeurs','publiclegal','genresJeu'));
    }

    public function adminDelete($id){
        \App\Jeuvideo::find($id)->genresJeu()->delete();
        \App\Jeuvideo::find($id)->delete();
        return redirect()->route('admin::videogame_all');
    }

    public function favoriteAdd(Request $request){
        $favorite = new \App\Favoris();
        $favorite->cli_id = Auth::id();
        $favorite->jeu_id = $request->jeu_id;
        $favorite->save();
        return response()->json('Ok');
    }

}
