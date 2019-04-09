<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class JeuVideo extends Model {

    protected $table = "t_e_jeuvideo_jeu";
    protected $primaryKey = "jeu_id";

    public $timestamps = false;

    public $dates = ['jeu_dateparution'];

    protected $fillable = ['jeu_dateparution','jeu_description','jeu_nom','edi_id','con_id','jeu_prixttc','jeu_publiclegal','jeu_stock','jeu_codebarre'];

    public function getSlugAttribute() {
        $slug = str_slug($this->jeu_nom);
        if (strlen($slug) > 50) $slug = substr($slug, 0, 50);
        return $slug;
    }

    public function avis(){
    	return $this->hasMany('App\Avis','jeu_id','jeu_id');
    }

    public function consoles(){
    	return $this->belongsTo('App\Console','con_id','con_id');
    }

    public function editeur(){
    	return $this->belongsTo('App\Editeur','edi_id','edi_id');
    }

    public function motscles(){
    	return $this->hasMany('App\MotCle','jeu_id','jeu_id');
    }

    public function photos(){
        return $this->hasMany('App\Photo','jeu_id','jeu_id');
    }

    public function videos(){
        return $this->hasMany('App\Video','jeu_id','jeu_id');
    }

    public function clientsalertes(){
        return $this->belongsToMany('App\Client','t_j_alerte_ale','jeu_id','cli_id');
    }

    public function clientsfavoris(){
        return $this->belongsToMany('App\Client','t_j_favori_fav','jeu_id','cli_id');
    }

    public function genres(){
        return $this->belongsToMany('App\Genre','t_j_genrejeu_gej','jeu_id','gen_id');
    }

        public function genresJeu(){
        return $this->hasMany('App\GenreJeu','jeu_id','jeu_id');
    }

    public function rayons(){
        return $this->belongsToMany('App\Rayon','t_j_jeurayon_jer','jeu_id','ray_id');
    }
}
