<?php
namespace App\Http\Controllers;

use App\Console;
use App\Genre;
use Session;
use Auth;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $GameConsoles = Console::get();
            $GameGenres = Genre::get();
            $GameConsoles=array('all' => 'Toutes les consoles') + $GameConsoles->pluck('con_nom', 'con_id')->all();
            $GameGenres=array('all' => 'Tous les genres') + $GameGenres->pluck('gen_libelle', 'gen_id')->all();
            $CART = collect(Session::get('cart', []));
            $USER = Auth::user();

            view()->share(compact('GameConsoles', 'GameGenres', 'CART', 'USER'));
            return $next($request);
        });
    }
}
