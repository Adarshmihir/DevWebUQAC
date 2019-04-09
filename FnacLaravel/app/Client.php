<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{

    protected $fillable = ['cli_pseudo','cli_motpasse','cli_mel','cli_pseudo','cli_civilite','cli_nom','cli_prenom','cli_telfixe','cli_telportable'];

    public $timestamps = false;
    protected $primaryKey = "cli_id";

    protected $table = 't_e_client_cli';

    public function getNameAttribute() {
        return "{$this->cli_prenom} {$this->cli_nom}";
    }
    public function getAuthPassword() { return $this->cli_motpasse; }
    public function getRememberToken(){return null;}
    public function setRememberToken($value){}
    public function getRememberTokenName() { return null; }

    public static function getCivilites() {
        return ['M.' => 'Monsieur', 'Mme' => 'Madame'];
    }

    public function adresses(){
    	return $this->hasMany('App\Adresse', 'cli_id', 'cli_id');
    }

    public function avis(){
    	return $this->hasMany('App\Avis','cli_id','cli_id');
    }

    public function avisabusifs(){
        return $this->belongsToMany('App\Avis','t_j_avisabusif_ava','cli_id','avi_id');
    }

    public function magasin(){
    	return $this->belongsTo('App\Magasin','mag_id','mag_id');
    }

    public function commandes(){
        return $this->hasMany('App\Commande','cli_id','cli_id');
    }

    public function alertes(){
        return $this->hasMany('App\Alerte','cli_id','cli_id');
    }

    public function jeuxvideosalertes(){
        return $this->belongsToMany('App\Jeuvideo','t_j_alerte_ale','cli_id','jeu_id');
    }

    public function jeuxvideosfavoris(){
        return $this->belongsToMany('App\Jeuvideo','t_j_favori_fav','cli_id','jeu_id');
    }
}
