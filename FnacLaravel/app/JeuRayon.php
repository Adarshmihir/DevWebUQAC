<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JeuRayon extends Model
{
    public $timestamps = false;

    protected $primaryKey = ["jeu_id", "ray_id"];

    protected $table = "t_j_jeurayon_jer";

    public function rayon(){
    	return $this->hasOne('App\Client','raay_id','ray_id');
    }

    public function jeuvideo(){
    	return $this->hasOne('App\Client','jeu_id','jeu_id');
    }
}
