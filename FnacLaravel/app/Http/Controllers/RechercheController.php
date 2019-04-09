<?php

namespace App\Http\Controllers;

use App\Jeuvideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechercheController extends Controller
{
    public function index() {
    	return view();
    }

    public function search(Request $request) {
		
        $games=Jeuvideo::with('photos', 'avis')->where('jeu_nom','ILIKE','%'.$request->q.'%');

        if($request->console && $request->console!='all'){
            $games->where('con_id',$request->console);
        }

        if($request->genre && $request->genre!='all'){
            /*$games->join('t_j_genrejeu_gej',function($join) use($request){
                $join->on('t_j_genrejeu_gej.jeu_id','t_e_jeuvideo_jeu.jeu_id');
                $join->on('t_j_genrejeu_gej.gen_id',DB::raw($request->genre));
            });*/
            $games->whereHas('genres', function($query) use ($request) {
                $query->where('t_j_genrejeu_gej.gen_id', $request->genre);
            });
        }

        $games = $games->get();

        if ($games->count() == 1)
        	return redirect()->route("videogame_one", [$games->first()->jeu_id, $games->first()->slug]);

        return view("search", compact('games'));
    }
}
