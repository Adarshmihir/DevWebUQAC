<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotCle extends Model
{
    
    public $timestamps = false;

    protected $primaryKey = "mot_id";

    protected $table = "t_e_motcle_mot";

    public function jeuxvideos(){
    	return $this->hasMany('App\Jeuvideo','jeu_id','jeu_id');
    }
}
