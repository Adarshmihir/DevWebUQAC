<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rayon extends Model
{
    public $timestamps = false;

    protected $primaryKey = "ray_id";

    protected $table = "t_r_rayon_ray";

    public function jeuxvideos(){
    	return $this->belongsToMany('App\Jeuvideo','t_j_jeurayon_jer','ray_id','jeu_id');
    }
}
